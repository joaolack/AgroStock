<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index()
    {   
        $products = Product::with('category')->orderBy('name')->paginate(10);
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required','string','max:150', Rule::unique('products', 'name')],
            'description' => ['nullable', 'string'],
            'selling_price' => ['required', 'numeric', 'min:0.01'],
            'cost_price' => ['required', 'numeric', 'min:0.'],
            'category_id' => ['required', 'exists:categories,id'],
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
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150', Rule::unique('products', 'name')->ignore($product->id)],
            'description' => ['nullable', 'string'],
            'selling_price' => ['required', 'numeric', 'min:0.01'],
            'cost_price' => ['required', 'numeric', 'min:0'],
            'category_id' => ['required', 'exists:categories,id'],
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

    public function moveStock(Request $request, Product $product)
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
            'operation' => ['required', 'in:input,output'],
        ]);

        $quantity = $validated['quantity'];
        $operation = $validated['operation'];

        $request->session()->flash('operation', $operation);

        if ($operation === 'input') {
            $product->stock_quantity += $quantity;
            $message = "✅ Entrada de **{$quantity}** unidades de {$product->name} registrada com sucesso!";
        
        } elseif ($operation === 'output') {
            if ($product->stock_quantity < $quantity) {
                return back()->withErrors(['quantity' => 'A quantidade de saída não pode ser maior que o estoque atual (' . $product->stock_quantity . ').'])
                        ->withInput();
            }
            $product->stock_quantity -= $quantity;
            $message = "✅ Saída de **{$quantity}** unidades de {$product->name} registrada com sucesso!";
        }

        $product->save();
        return redirect()->route('products.edit', $product->id)->with('success', $message);
    }
}
