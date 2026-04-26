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
        
        $query = Product::with(['category', 'supplier']);

        //Name or description search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        //Category fillter
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        //Status fillter
        if ($request->filled('stock_status')) {
            switch($request->stock_status) {
                case 'Em Falta':
                    $query->where('stock_quantity', 0);
                    break;
                case 'Estoque Baixo':
                    $query->whereColumn('stock_quantity', '<=', 'minimum_stock');
                    break;
                case 'Estoque Normal':
                    $query->whereColumn('stock_quantity', '>', 'minimum_stock');    
                    break;        
            }   
        }
    
        $products = $query->orderBy('name')->paginate(10)->withQueryString();
        $categories = Category::all();

        if ($request->ajax()) {
            return view('products.partials.table', compact('products'))->render();
        }

        $totalProducts = Product::count();

        $criticalStock = Product::whereColumn('stock_quantity', '<=', 'minimum_stock')
                                   ->with('category')
                                   ->orderBy('stock_quantity', 'asc')
                                   ->get();

        $outOfStockProducts = Product::where('stock_quantity', 0)->count();

        $totalStockValue = Product::select(DB::raw('SUM(cost_price * stock_quantity) as total_cost'))
                                      ->value('total_cost') ?? 0;

        
        return view('products.index', compact('products', 'categories', 'totalProducts', 'criticalStock', 'outOfStockProducts', 'totalStockValue'));
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
            'supplier_id' => ['nullable', 'exists:suppliers,id'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'minimum_stock' => ['required', 'integer', 'min:0'],
            'expiration_date' => ['nullable', 'date', 'after:today'],
            
        ]);

        Product::create($validated);

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
