<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class StockMovementController extends Controller
{
    public function index(Request $request)
    {
        $stockMovements = StockMovement::with(['product', 'productBatch.supplier', 'user'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('product_id')) {
            $stockMovements->where('product_id', $request->product_id);
        }

        if ($request->filled('type')) {
            $stockMovements->where('type', $request->type);
        }

        if ($request->filled('date_from')) {
            $stockMovements->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $stockMovements->whereDate('created_at', '<=', $request->date_to);
        }

        $movements = $stockMovements->paginate(15);
        $products = Product::with('availableBatches')->orderBy('name')->get();
        $suppliers = Supplier::active()->orderBy('name')->get();

        return view('stock-movements.index', compact('movements', 'products', 'suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'type' => ['required', 'in:entry,exit'],
            'batch_number' => [
                'nullable',
                'string',
                'max:100',
                Rule::requiredIf(fn () => $request->input('type') === 'entry'),
                Rule::unique('product_batches', 'number')->where(fn ($query) => $query->where('product_id', $request->input('product_id'))),
            ],
            'supplier_id' => ['nullable', 'exists:suppliers,id', Rule::requiredIf(fn () => $request->input('type') === 'entry')],
            'expiration_date' => ['nullable', 'date', 'after:today'],
        ]);

        $quantity = $validated['quantity'];
        $type = $validated['type'];

        $message = DB::transaction(function () use ($validated, $quantity, $type) {
            $product = Product::whereKey($validated['product_id'])->lockForUpdate()->firstOrFail();
            $previousQuantity = $product->stock_quantity;

            if ($type === 'entry') {
                $batch = $product->batches()->create([
                    'supplier_id' => $validated['supplier_id'],
                    'number' => $validated['batch_number'],
                    'original_quantity' => $quantity,
                    'quantity' => $quantity,
                    'expiration_date' => $validated['expiration_date'] ?? null,
                ]);

                $product->stock_quantity += $quantity;
                $product->supplier_id = $validated['supplier_id'];
                $product->expiration_date = $validated['expiration_date'] ?? null;
                $product->save();

                StockMovement::create([
                    'product_id' => $product->id,
                    'product_batch_id' => $batch->id,
                    'user_id' => auth()->id(),
                    'type' => $type,
                    'quantity' => $quantity,
                    'previous_quantity' => $previousQuantity,
                    'new_quantity' => $product->stock_quantity,
                ]);

                return "Entrada de {$quantity} unidades registrada no lote {$batch->number}.";
            }

            if ($product->stock_quantity < $quantity) {
                throw ValidationException::withMessages([
                    'quantity' => 'Estoque insuficiente! Disponivel: ' . $product->stock_quantity,
                ]);
            }

            $remaining = $quantity;

            foreach ($product->availableBatches()->lockForUpdate()->get() as $batch) {
                if ($remaining <= 0) {
                    break;
                }

                $consumed = min($batch->quantity, $remaining);
                $movementPreviousQuantity = $product->stock_quantity;

                $batch->quantity -= $consumed;
                $batch->save();

                $product->stock_quantity -= $consumed;
                $product->save();

                StockMovement::create([
                    'product_id' => $product->id,
                    'product_batch_id' => $batch->id,
                    'user_id' => auth()->id(),
                    'type' => $type,
                    'quantity' => $consumed,
                    'previous_quantity' => $movementPreviousQuantity,
                    'new_quantity' => $product->stock_quantity,
                ]);

                $remaining -= $consumed;
            }

            if ($remaining > 0) {
                throw ValidationException::withMessages([
                    'quantity' => 'Estoque sem lotes suficientes para saida FIFO. Disponivel em lotes: ' . ($quantity - $remaining),
                ]);
            }

            return "Saida de {$quantity} unidades registrada usando FIFO.";
        });

        return redirect()->route('stock-movements.index')
            ->with('success', $message);
    }
}
