@extends('layouts.app')

@section('slot')
<div class="py-12">
    <div class="max-w-full mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white dark:bg-gray-800">
                <h1 class="text-3xl font-semibold text-gray-800 dark:text-gray-200 mb-10">
                    Lista de Categorias
                </h1>

                <a href="{{ route('categories.create') }}" class="bg-green-600 hover:bg-[#015724] text-white font-bold py-3 px-4 rounded transition duration-200 inline-block">
                    + Nova Categoria
                </a>

                <div class="overflow-x-auto mt-6 rounded">
                    <table class="w-full">
                        <thead class="bg-gray-100 dark:bg-gray-700 border-x border-gray-100 dark:border-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium
                                    text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Nome da Categoria
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium
                                    text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Descrição
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium
                                    text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Qtd. de Produtos
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium
                                    text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Ações
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-600 border border-gray-300 dark:border-gray-600 rounded">
                            @forelse ($categories as $category)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $category->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $category->description ?? '-'}}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $category->products_count }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('categories.edit', $category->id) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-3 transition duration-150">
                                        Editar
                                    </a>

                                    @if ($category->products_count > 0)
                                        <!--Modal de aviso -->
                                        <div x-data="{ open: false }" class="inline-block">
                                            <button type="button" @click="open = true" class="text-orange-600 hover:text-orange-900 dark:text-orange-400 dark:hover:text-orange-300 transition duration-150">
                                                🔒 Excluir
                                            </button>

                                            <!-- Modal -->
                                            <div x-show="open" x-cloak @click.away="open = false" class="fixed inset-0 z-[9999] overflow-y-auto" style="display: none;" @keydown.escape.window="open = false">

                                                <!--Backdrop-->
                                                <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity z-[9998]"></div>
                                                
                                                <!--Modal Content-->
                                                <div class="flex min-h-screen items-center justify-center p-4">
                                                    <div 
                                                        @click.stop
                                                        x-transition:enter="transition ease-out duration-300"
                                                        x-transition:enter-start="opacity-0 transform scale-95"
                                                        x-transition:enter-end="opacity-100 transform scale-100"
                                                        x-transition:leave="transition ease-in duration-200"
                                                        x-transition:leave-start="opacity-100 transform scale-100"
                                                        x-transition:leave-end="opacity-0 transform scale-95"
                                                        class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-xl w-full p-6 z-[9999]">
                                                        
                                                        <!--Header-->
                                                        <div class="flex items-center mb-4">
                                                            <div class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-orange-100 dark:bg-orange-900">
                                                                <svg class="h-6 w-6 text-orange-600 dark:text-orange-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                                                </svg>
                                                            </div>
                                                            <h3 class="ml-4 text-lg font-medium text-gray-900 dark:text-gray-100 break-words overflow-wrap-anywhere">
                                                                Não é possivel excluir.
                                                            </h3>
                                                        </div>

                                                        <!--Content-->
                                                        <div class="space-y-3"> 
                                                            <p class="text-sm text-gray-600 dark:text-gray-400 break-words" ">
                                                                A categoria <strong>"{{ $category->name }}"</strong> possui <strong class="text-orange-600">{{ $category->products_count }} produto(s)</strong>
                                                            </p>
                                                            <div class="bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500 p-3 rounded">
                                                                <p class="text-sm text-blue-800 dark:text-blue-200 break-words">
                                                                    💡 <strong> Dica:</strong> Remova ou altere a categoria dos produtos vinculados primeiro.
                                                                </p>
                                                            </div>    
                                                        </div>

                                                        <div class="flex justify-end">
                                                            <button type="button" @click.prevent="open = false" class="px-4 py-2 mt-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                                                                Entendi
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>    
                                    @else
                                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Tem certeza que deseja excluir esta categoria?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition duration-150"> 
                                                Excluir
                                            </button>
                                        </form>
                                    @endif    
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">Nenhuma categoria cadastrada ainda.
                                    <a href="{{ route('categories.create') }}" class="text-green-600 hover:underline ml-2">
                                        Criar primeira categoria
                                    </a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($categories->hasPages())
                    <div class="mt-4">
                        {{ $categories->links() }}
                    </div>
                @endif    
            </div>
        </div>
    </div>
</div>

@endsection
