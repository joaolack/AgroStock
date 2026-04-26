@extends('layouts.app')

@section('slot')
<div class="flex-1 flex flex-col min-h-screen overflow-hidden">

    <header class="sticky top-0 z-20 flex items-center justify-between px-6 py-3.5 border-b bg-white/80 backdrop-blur-md"
            style="border-color:#d4e8d6;">
        <div class="flex items-center gap-3">
            <button class="lg:hidden p-2 rounded-lg hover:bg-agro-pale transition colors" style="color:#4a5c4c;">
                ☰
            </button>
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
                </a>
            <div>
                <h1 class="font-display text-xl font-bold tracking-tight" style="color:#1a3df;">Editar Produto</h1>
                <p class="text-[11px]" style="color:#8a9e8c;">Altere as informações do produto</p>
            </div>
        </div>

        <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold text-white cursor-pointer"
                 style="background:linear-gradient(135deg,#4caf50,#2d6a35);">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
        </div>
    </header>


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
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="supplier_id" class="block text-sm font-medium text-gray-900 dark:text-gray-100">
                            Fornecedor:
                        </label>
                        <select id="supplier_id" name="supplier_id" class="form-select mt-1 block rounded-lg shadow-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-green-500 focus:ring-green-500 @error('supplier_id') border-red-500 @enderror">
                            <option value="">Selecione um fornecedor</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ old('supplier_id', $product->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('supplier_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
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
            </div>
        </div>
    </div>        
</div>    
@endsection
