@extends('layouts.app')

@section('slot')

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
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

                <h1 class="text-3xl font-semibold text-gray-800 dark:text-gray-200 mb-6"> 
                    Cadastrar Novo Produto
                </h1>

                @if ($errors->any())
                    <div class="alert-error mb-4">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif


                <form action="{{ route('products.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-900 dark:text-gray-100">
                            Nome do Produto: <span class="text-red-600">*</span>
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" class="form-input mt-1 block w-full rounded-lg shadow-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-green-500 focus:ring-green-500 @error('name') border-red-500 @enderror" placeholder="Nome do produto" required>
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="category_id" class="block text-sm font-medium text-gray-900 dark:text-gray-100">
                            Categoria: <span class="text-red-600">*</span>
                        </label>
                        <select id="category_id" name="category_id" class="form-select mt-1 block rounded-lg shadow-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-green-500 focus:ring-green-500 @error('category_id') border-red-500 @enderror" required>
                            <option value="">Selecione uma categoria</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach    
                        </select>
                        @error('category_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="cost_price" class="block text-sm font-medium text-gray-900 dark:text-gray-100">
                            Preço de Custo (R$) <span class="text-red-600">*</span>
                        </label>
                        <input type="number" step="0.01" min="0" id="cost_price" value="{{ old('cost_price') }}" name="cost_price" class="form-input mt-1 block w-full rounded-lg shadow-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-green-500 focus:ring-green-500 @error('cost_price') border-red-500 @enderror rounded-lg shadow-md" placeholder="0,00" required>
                        @error('cost_price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="selling_price" class="block text-sm font-medium text-gray-900 dark:text-gray-100">
                            Preço de Venda (R$) <span class="text-red-600">*</span>
                        </label>
                        <input type="number" step="0.01" min="0.01" id="selling_price" name="selling_price" value="{{ old('selling_price') }}" class="form-input mt-1 block w-full rounded-lg shadow-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-green-500 focus:ring-green-500 @error('selling_price') border-red-500 @enderror rounded-lg shadow-md" placeholder="0,00" required>
                        @error('selling_price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="stock_quantity" class="block text-sm font-medium text-gray-900 dark:text-gray-100">
                            Quantidade em Estoque: <span class="text-red-600">*</span>
                        </label>
                        <input type="number" min="0" id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity') }}" class="form-input mt-1 block w-full rounded-lg shadow-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-green-500 focus:ring-green-500 @error('stock_quantity') border-red-500 @enderror rounded-lg shadow-md" placeholder="0" required>
                        @error('stock_quantity') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="minimum_stock" class="block text-sm font-medium text-gray-900 dark:text-gray-100">
                            Estoque Mínimo: <span class="text-red-600">*</span>
                        </label>
                        <input type="number" min="0" id="minimum_stock" name="minimum_stock" value="{{ old('minimum_stock') }}" class="form-input mt-1 block w-full rounded-lg shadow-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-green-500 focus:ring-green-500 @error('minimum_stock') border-red-500 @enderror rounded-lg shadow-md" placeholder="0" required>
                        @error('minimum_stock') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="expiration_date" class="block text-sm font-medium text-gray-900 dark:text-gray-100">
                            Data de Validade: 
                        </label>
                        <input type="date" id="expiration_date" name="expiration_date" value="{{ old('expiration_date') }}" class="form-input mt-1 block w-full rounded-lg shadow-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-green-500 focus:ring-green-500 @error('expiration_date') border-red-500 @enderror rounded-lg shadow-md">
                        @error('expiration_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-900 dark:text-gray-100">
                            Descrição do Produto: <span class="text-red-600">*</span>
                        </label>
                        <textarea id="description" name="description" rows="3" class="form-textarea mt-1 block w-full rounded-lg shadow-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-green-500 focus:ring-green-500 @error('description') border-red-500 @enderror rounded-lg shadow-md" placeholder="Descreva o produto..." required></textarea>
                        @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mt-8 pt-4 border-t border-gray-200">
                        <button type="submit" class="bg-green-600 hover:bg-[#015724] text-white font-bold py-3 px-4 rounded transition duration-200 inline-block">
                            Cadastrar Produto
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