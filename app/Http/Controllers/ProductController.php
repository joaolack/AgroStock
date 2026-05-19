<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(Request $request)
    {   
        $filters = [
            'search' => trim((string) $request->input('search', '')),
            'category_id' => $request->input('category_id') ?: '',
            'stock_status' => $request->input('stock_status') ?: '',
        ];
        
        $query = Product::with(['category', 'supplier', 'batches.supplier']);

        $query->when($filters['search'] !== '', function ($query) use ($filters) {
            $search = $filters['search'];

            $query->where(function ($q) use ($search) {
                if (mb_strlen($search) <= 2) {
                    $q->where('name', 'LIKE', "{$search}%")
                        ->orWhere('name', 'LIKE', "% {$search}%");

                    return;
                }

                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%");

                if (mb_strlen($search) >= 3) {
                    $q->orWhereHas('category', function ($categoryQuery) use ($search) {
                        $categoryQuery->where('name', 'LIKE', "%{$search}%");
                    })->orWhereHas('supplier', function ($supplierQuery) use ($search) {
                        $supplierQuery->where('name', 'LIKE', "%{$search}%");
                    });
                }
            });
        });

        //Category fillter
        if ($filters['category_id'] !== '') {
            $query->where('category_id', $filters['category_id']);
        }

        //Status fillter
        if ($filters['stock_status'] !== '') {
            switch($filters['stock_status']) {
                case 'Em Falta':
                    $query->where('stock_quantity', 0);
                    break;
                case 'Estoque Baixo':
                    $query->where('stock_quantity', '>', 0)
                        ->whereColumn('stock_quantity', '<=', 'minimum_stock');
                    break;
                case 'Estoque Normal':
                    $query->whereColumn('stock_quantity', '>', 'minimum_stock');    
                    break;        
            }   
        }
    
        $products = $query->orderBy('name')->paginate(10)->withQueryString();
        $categories = Category::all();

        $totalProducts = Product::count();

        $criticalStock = Product::whereColumn('stock_quantity', '<=', 'minimum_stock')
                                   ->with('category')
                                   ->orderBy('stock_quantity', 'asc')
                                   ->get();

        $outOfStockProducts = Product::where('stock_quantity', 0)->count();

        $totalStockValue = Product::select(DB::raw('SUM(cost_price * stock_quantity) as total_cost'))
                                      ->value('total_cost') ?? 0;

        
        return view('products.index', compact('products', 'categories', 'filters', 'totalProducts', 'criticalStock', 'outOfStockProducts', 'totalStockValue'));
    }

    public function create()
    {
        $categories = Category::all();
        $suppliers = Supplier::active()->orderBy('name')->get();

        return view('products.create', compact('categories', 'suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required','string','max:150', Rule::unique('products', 'name')],
            'description' => ['nullable', 'string'],
            'selling_price' => ['required', 'numeric', 'min:0.01'],
            'cost_price' => ['required', 'numeric', 'min:0.'],
            'category_id' => ['required', 'exists:categories,id'],
            'supplier_id' => [Rule::requiredIf(fn () => (int) $request->input('stock_quantity', 0) > 0), 'nullable', 'exists:suppliers,id'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'minimum_stock' => ['required', 'integer', 'min:0'],
            'expiration_date' => [Rule::requiredIf(fn () => (int) $request->input('stock_quantity', 0) > 0), 'nullable', 'date', 'after:today'],
            'batch_number' => [
                Rule::requiredIf(fn () => (int) $request->input('stock_quantity', 0) > 0),
                'nullable',
                'string',
                'max:100',
            ],
            
        ]);

        DB::transaction(function () use ($validated) {
            $batchNumber = $validated['batch_number'] ?? null;
            unset($validated['batch_number']);

            $product = Product::create($validated);

            if ($product->stock_quantity > 0) {
                $product->batches()->create([
                    'supplier_id' => $product->supplier_id,
                    'number' => $batchNumber,
                    'original_quantity' => $product->stock_quantity,
                    'quantity' => $product->stock_quantity,
                    'expiration_date' => $product->expiration_date,
                ]);
            }
        });

        return redirect()->route('products.index')
                       ->with('success', 'Produto cadastrado com sucesso!');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $suppliers = Supplier::active()->orderBy('name')->get();

        return view('products.edit', compact('product', 'categories', 'suppliers'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150', Rule::unique('products', 'name')->ignore($product->id)],
            'description' => ['nullable', 'string'],
            'selling_price' => ['required', 'numeric', 'min:0.01'],
            'cost_price' => ['required', 'numeric', 'min:0'],
            'category_id' => ['required', 'exists:categories,id'],
            'supplier_id' => ['nullable', 'exists:suppliers,id'],
            'minimum_stock' => ['required', 'integer', 'min:0'],
            'expiration_date' => ['nullable', 'date', 'after:today'],
        ]);

        $product->update($validated);
        return redirect()->route('products.index')->with('success', 'Produto atualizado com sucesso');

    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Produto excluído com sucesso');
    }
}
