<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\StockMovement;

class StockMovementController extends Controller
{
    public function index(Request $request)
    {
        $stockMovements = StockMovement::with(['product', 'user'])->orderBy('created_at', 'desc');

        if ($request->filled('product_id')){
            $stockMovements->where('product_id', $request->product_id);
        }

        if ($request->filled('type')) {
            $stockMovements->where('type', $request->type);
        }

        if ($request->filled('date_from')){
            $stockMovements->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')){
            $stockMovements->whereDate('created_at', '<=', $request->date_to);
        }

        $movements = $stockMovements->paginate(15);
        $products = Product::orderBy('name')->get();

        return view('stock-movements.index', compact('movements', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'type' => ['required', 'in:entry,exit'],
        ]);

        $product = Product::findOrFail($validated['product_id']);
        $quantity = $validated['quantity'];
        $previousQuantity = $product->stock_quantity;
        $type = $validated['type'];

        if ($type === 'exit' && $product->stock_quantity < $quantity) {
            return back()->withErrors(['quantity' => 'Estoque insuficiente! Disponível: ' . $product->stock_quantity])->withInput();
        }

        if ($type === 'entry') {
            $product->stock_quantity += $quantity;
            $message = "Entrada de {$quantity} unidades registrada com sucesso!";
        } else {
            $product->stock_quantity -= $quantity;
            $message = "✅ Saída de {$quantity} unidades registrada com sucesso!";
        }

        $product->save();

        stockMovement::create([
            'product_id' => $product->id,
            'user_id' => auth()->id(),
            'type' => $type,
            'quantity' => $quantity,
            'previous_quantity' => $previousQuantity,
            'new_quantity' => $product->stock_quantity,
        ]);

        return redirect()->route('stock-movements.index')
            ->with('success', $message);
    }
}
