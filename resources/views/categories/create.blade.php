@extends('layouts.app')

@section('slot')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white dark:bg-gray-800">
                    <a href="{{ route('categories.index') }}"
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
                        Cadastrar nova Categoria
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
                    
                    <form action="{{ route('categories.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-900 dark:text-gray-100">
                                Nome da Categoria: <span class="text-red-600">*</span>
                            </label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" class="form-input mt-1 block w-full rounded-lg shadow-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-green-500 focus:ring-green-500 @error('name') border-red-500 @enderror" placeholder="Ex: Rações, Medicamentos, Ferramentas..." required autofocus>
                            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-6">
                            <label for="description" class="block text-sm font-medium text-gray-900 dark:text-gray-100">
                                Descrição:
                            </label>
                            <textarea id="description" name="description" rows="3" class="form-textarea mt-1 block w-full rounded-lg shadow-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-green-500 focus:ring-green-500 @error('description') border-red-500 @enderror" placeholder="Descreva o tipo de produtos desta categoria..." autofocus>{{ old('description') }}</textarea>
                            @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="mt-8 pt-4 border-t border-gray-200">
                            <button type="submit" class="bg-green-600 hover:bg-[#015724] text-white font-bold py-3 px-4 rounded transition duration-200 inline-block">
                                Cadastrar Categoria
                            </button>
                            <a href="{{ route('categories.index') }}" class="bg-red-600 hover:bg-[#7A0C0C] text-white font-bold py-3 px-4 rounded transition duration-200 inline-block">
                                Cancelar
                            </a>
                        </div>    
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection