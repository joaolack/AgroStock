<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Repositories\ProductRepository;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductRepository $productRepository,
        private readonly ProductService $productService
    ) {}

    public function index(Request $request)
    {
        return view('products.index', $this->productRepository->indexData($request->input()));
    }

    public function create()
    {
        return view('products.create', [
            'categories' => $this->productRepository->categories(),
            'suppliers' => $this->productRepository->activeSuppliers(),
        ]);
    }

    public function store(StoreProductRequest $request)
    {
        $this->productService->createWithInitialBatch($request->validated());

        return redirect()->route('products.index')
            ->with('success', 'Produto cadastrado com sucesso!');
    }

    public function edit(Product $product)
    {
        return view('products.edit', [
            'product' => $product->load('batches'),
            'categories' => $this->productRepository->categories(),
            'suppliers' => $this->productRepository->activeSuppliers(),
        ]);
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $this->productService->update($product, $request->validated());

        return redirect()->route('products.index')->with('success', 'Produto atualizado com sucesso');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produto excluído com sucesso');
    }
}
