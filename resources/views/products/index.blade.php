@extends('layouts.app')
    
@section('slot')
<div class="py-12">
    <div class="max-w-full mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white dark:bg-gray-800">
                <h1 class="text-3xl font-semibold text-gray-800 dark:text-gray-200
                mb-10">
                    Lista de Produtos
                </h1>

                <a href="{{ route('products.create') }}" class="bg-green-600 hover:bg-[#015724] text-white font-bold py-3 px-4 rounded transition duration-200 inline-block">
                    + Novo Produto
                </a>

                <div class="overflow-x-auto mt-6 rounded">
                    <table class="w-full">
                        <thead class="bg-gray-100 dark:bg-gray-700 border-x border-gray-100 dark:border-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium
                                    text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Produto
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium
                                    text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Estoque Atual
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium
                                    text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Preço Venda
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium
                                    text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Status
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium
                                    text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Categoria
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium
                                    text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Descrição
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium
                                    text-gray-500 dark:text-gray-300 uppercase tracking-wider text-center">
                                    Validade
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium
                                    text-gray-500 dark:text-gray-300 uppercase tracking-wider text-center">
                                    Ações
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-600 border border-gray-300 dark:border-gray-600 rounded">
                            @forelse ($products as $product)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $product->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">
                                    <span class="font-semibold {{ $product->stock_quantity <= 5 ? 'text-red-600' : 'text-green-600' }}">
                                        {{ $product->stock_quantity }}
                                    </span>
                                    <small class="text-gray-500 block">Min: {{ $product->minimum_stock }}</small>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">R$ {{ number_format($product->selling_price, 2, ',', '.') }}</td>
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    @php
                                        $status = $product->stock_status;
                                        $class = '';
                                        if ($status == 'Em Falta') $class = 'bg-red-100 text-red-800';
                                        else if ($status == 'Estoque Baixo') $class = 'bg-yellow-100 text-yellow-800';
                                        else $class = 'bg-green-100 text-green-800';
                                    @endphp
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $class }}">
                                        {{ $status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $product->category->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $product->description }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500 text-center">
                                    @if ($product->expiration_date)
                                        @php
                                            $expiryDate = \Carbon\Carbon::parse($product->expiration_date)->startOfDay();
                                            $today = \Carbon\Carbon::now()->startOfDay();
                                            $statusDays = $today->diffInDays($expiryDate, false);  
                                            $absoluteDays = abs($statusDays); 
                                        @endphp
                                        @if ($statusDays < 0) 
                                            <span class="text-red-500 font-bold">EXPIRADO HÁ {{ $absoluteDays }} DIAS!</span>
                                        @elseif ($statusDays === 0)
                                            <span class="text-red-600 font-bold">VENCE HOJE!</span>
                                        @elseif ($statusDays <= 30) 
                                            <span class="text-yellow-600">Vence em {{ $absoluteDays }} dias</span>
                                        @else
                                            {{ $expiryDate->format('d/m/Y') }}
                                        @endif
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('products.edit', $product->id) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-3 transition duration-150">
                                        Editar
                                    </a>
                            
                                    <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline-block" onsubmit="return confirm('Tem certeza que deseja excluir este produto?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition duration-150">
                                            Excluir
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty 
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $products->links()}}
                </div>
            </div>    
        </div>
    </div>
</div>
@endsection