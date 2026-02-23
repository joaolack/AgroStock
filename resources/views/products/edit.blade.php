@extends('layouts.app')

@section('slot')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white dark:bg-gray-800">
                <a href="{{ route('products.index') }}"
                        class="inline-flex items-center text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-green-600 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4 mr-2"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M15 19l-7-7 7-7" />
                        </svg>
                    Voltar
                </a>

                <h1 class="text-3xl font-semibold text-gray-800 dark:text-gray-200 mb-6">Editar Produto: {{ $product->name }}</h1>
                <form action="{{ route('products.update', $product->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-900 dark:text-gray-100">
                            Nome do Produto: <span class="text-red-600">*</span>
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" class="form-input mt-1 block w-full rounded-lg shadow-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-green-500 focus:ring-green-500" required>
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="category_id" class="block text-sm font-medium text-gray-900 dark:text-gray-100">
                            Categoria: <span class="text-red-600">*</span>
                        </label>
                        <select id="category_id" name="category_id" class="form-select mt-1 block rounded-lg shadow-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-green-500 focus:ring-green-500" required>
                            <option value="">Selecione uma categoria</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">
                                    {{ old('category_id', $product->category_id) == $category->id ? 'atual' : ''}}
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="cost_price" class="block text-sm font-medium text-gray-900 dark:text-gray-100">
                            Preço de Custo (R$) <span class="text-red-600">*</span>
                        </label>
                        <input type="number" step="0.01" min="0" id="cost_price" name="cost_price" value="{{ old('cost_price', $product->cost_price) }}" class="form-input mt-1 block w-full rounded-lg shadow-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-green-500 focus:ring-green-500" required>
                        @error('cost_price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror    
                    </div>

                    <div class="mb-4">
                        <label for="selling_price" class="block text-sm font-medium text-gray-900 dark:text-gray-100">
                            Preço de Venda (R$) <span class="text-red-600">*</span>
                        </label>
                        <input type="number" step="0.01" min="0.01" id="selling_price" name="selling_price" value="{{ old('selling_price', $product->selling_price) }}" class="form-input mt-1 block w-full rounded-lg shadow-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-green-500 focus:ring-green-500" required>
                        @error('selling_price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="minimum_stock" class="block text-sm font-medium text-gray-900 dark:text-gray-100">
                            Estoque Mínimo: <span class="text-red-600">*</span>
                        </label>
                        <input type="number" min="0" id="minimum_stock" name="minimum_stock" value="{{ old('minimum_stock', $product->minimum_stock) }}" class="form-input mt-1 block w-full rounded-lg shadow-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-green-500 focus:ring-green-500" required>
                        @error('minimum_stock') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="expiration_date" class="block text-sm font-medium text-gray-900 dark:text-gray-100">
                            Data de Validade: <span class="text-red-600">*</span>
                        </label>
                        <input type="date" id="expiration_date" name="expiration_date" value="{{ old('expiration_date', $product->expiration_date ? $product->expiration_date->format('Y-m-d') : '') }}" class="form-input mt-1 block w-full rounded-lg shadow-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-green-500 focus:ring-green-500">
                        @error('expiration_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror  
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-900 dark:text-gray-100">
                            Descrição: <span class="text-red-600">*</span>
                        </label>
                        <textarea id="description" name="description" rows="3" class="form-input mt-1 block w-full rounded-lg shadow-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-green-500 focus:ring-green-500">{{ old('description', $product->description) }}</textarea>
                        @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mt-8 pt-4 border-t border-gray-200">
                        <button type="submit" class="bg-green-600 hover:bg-[#015724] text-white font-bold py-3 px-4 rounded transition duration-200 inline-block">
                            Salvar Alterações
                        </button>
                        <a href="{{ route('products.index') }}" class="bg-red-600 hover:bg-[#7A0C0C] text-white font-bold py-3 px-4 rounded transition duration-200 inline-block">
                            Cancelar  
                        </a>
                    </div>
                </form>

                <hr class="my-8">

                <h2 class="text-2xl font-semibold mb-4"> Movimentação de Estoque</h2>
                <p class="mb-2">Estoque Atual: **{{ $product->stock_quantity }}**</p>
                <p class="mb-4 text-sm text-gray-500">Estoque Mínimo: **{{ $product->minimum_stock }}**</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="p-6 border border-green-300 rounded-lg bg-green-50">
                        <h3 class="text-xl font-medium text-green-700 mb-4">Entrada (Adicionar)</h3>
                        <form action="{{ route('products.moveStock', $product->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="operation" value="input">
                            <div class="mb-4">
                                <label for="inputQuantity" class="block text-sm font-medium text-gray-900 dark:text-gray-100">
                                    Quantidade a Adicionar: <span class="text-red-600">*</span>
                                </label>
                                <input type="number" min="1" id="inputQuantity" name="quantity" value="{{ old('quantity') }}" required class="form-input mt-1 block w-full rounded-lg shadow-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-green-500 focus:ring-green-500">
                                @if (session('operation') == 'input') 
                                    @error('quantity') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                @endif    
                            </div>
                            <button type="submit" class="bg-green-600 hover:bg-[#015724] text-white font-bold py-2 px-4 rounded transition duration-200">
                                Registrar Entrada
                            </button>
                        </form> 
                    </div>

                    <div class="p-6 border border-red-300 rounded-lg bg-red-50">
                        <h3 class="text-xl font-medium text-red-700 mb-4">Saída (Remover)</h3>
                        <form action="{{ route('products.moveStock', $product->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="operation" value="output">
                            <div class="mb-4">
                                <label for="outputQuantity" class="block text-sm font-medium text-gray-900 dark:text-gray-100">
                                    Quantidade a Remover: <span class="text-red-600">*</span>
                                </label>
                                <input type="number" min="1" id="outputQuantity" name="quantity" value="{{ old('quantity') }}" required class="form-input mt-1 block w-full rounded-lg shadow-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-red-500 focus:ring-red-500">
                                @if (session('operation') == 'output')
                                    @error('quantity') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                @endif    
                            </div>
                            <button type="submit" class="bg-red-600 hover:bg-[#7A0C0C] text-white font-bold py-2 px-4 rounded transition duration-200">
                                Registrar Saída
                            </button>    
                        </form>
                    </div>
            </div>
        </div>
    </div>        
</div>    
@endsection