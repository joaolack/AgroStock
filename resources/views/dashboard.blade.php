@extends('layouts.app')

@section('slot')
<div class="max-w-7xl mx-auto py-8 sm:px-6 lg:px-8">
    <h2 class="text-3xl font-semibold text-gray-800 dark:text-gray-100 mb-6">
        {{ __('Início (Dashboard)') }}
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="card bg-white p-6 rounded-lg shado-md border-l-4 border-indigo-500">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total de Produtos Cadastrados</p>
            <p class="text-3xl font-bold text-gray-900"> {{ number_format($totalProducts, 0, ',', '.')}}</p>
        </div>

        <div class="card bg-white p-6 rounded-lg shadow-md border-l-4 border-red-500">
            <p class="text-sm font-medium text-gray-500">Produtos Em Falta (Zero)</p>
            <p class="text-3xl font-bold text-red-500">{{ $outOfStockProducts}}</p>
        </div>

        <div class="card bg-white p-6 rounded-lg shadow-md border-l-4 border-green-500">
            <p class="text-sm font-medium text-gray-500">Valor Total do Estoque (Custo)</p>
            <p class="text-xl font-bold text-gray-900">R$ {{ number_format($totalStockValue, 2, ',', '.')}}</p>
        </div>

        <div class="card bg-white p-6 rounded-lg shadow-md border-l-4 border-yellow-500">
            <p class="text-sm font-medium text-gray-500">Total de Itens Críticos</p>
            {{-- Calcula o total de produtos que estão abaixo ou igual ao estoque mínimo --}}
            <p class="text-3xl font-bold text-yellow-600">{{ $criticalStock->count() }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-xl font-semibold mb-4 text-red-700 border-b pb-2">Itens Abaixo do Estoque Mínimo</h3>
            @if ($criticalStock->isEmpty())
                <p class="text-gray-500">Nenhum alerta de estoque. Tudo sob controle!</p>
            @else
                <ul class="divide-y divide-gray-100">
                    @foreach ($criticalStock->take(5) as $product)
                        <li class="py-3 flex justify-between itens-center text-sm">
                            <span class="font-medium text-gray-900">{{ $product->name }}</span>
                            <span class="text-red-600 font-bold">
                                {{ $product->stock_quantity }} / Min: {{ $product->minimum_stock}}
                            </span>
                        </li>
                    @endforeach
                    @if ($criticalStock->count() > 5)
                    <li class="pt-3 text-center text-sm">
                        <a href="{{ route('products.index') }}" class="text-indigo-600 hover:underline"> Ver todos os {{ $criticalStock->count() }} alertas</a>       
                    </li>
                    @endif    
                </ul>
            @endif        
        </div>
    
        <div class="bg-white p-6 rounded-lg shado-md">
            <h3 class="text-xl font-semibold mb-4 text-yellow-700 border-b pb-2">Produtos com Vencimento próximo</h3>
            @if ($closeToExpiry->isEmpty())
                <p class="text-gray-500">Nenhum produto vencendo em breve.</p>
            @else
                <ul class="divide-y divide-gray-100">
                    @foreach ($closeToExpiry->take(5) as $product)
                        <li class="py-3 flex justify-between itens-center text-sm">
                            <span class="font-medium text-gray-900">{{ $product->name }}</span>
                            @php
                                $expiryDate = \Carbon\Carbon::parse($product->expiration_date)->startOfDay();
                                $today = \Carbon\Carbon::now()->startOfDay();
                                $statusDays = $today->diffInDays($expiryDate, false); 
                                $absoluteDays = abs($statusDays); 
                                $expirationDate = $expiryDate->format('d/m/Y');
                            @endphp
                            @if ($statusDays < 0) 
                                <span class="text-red-600 font-bold">EXPIRADO HÁ {{ $absoluteDays }} DIAS ({{ $expirationDate}})</span>
                            @elseif ($statusDays === 0)
                                <span class="text-red-600 font-bold">VENCE HOJE ({{ $expirationDate}})</span>
                            @else
                                <span class="text-yellow-600">Vence em {{ $absoluteDays}} dias ({{ $expirationDate}})</span>
                            @endif
                        </li>
                    @endforeach
                    <li class="pt-3 text-center text-sm">
                        <a href="{{ route('products.index') }}" class="text-indigo-600 hover:underline">Ver todos</a>
                    </li>
                </ul>
            @endif 
        </div>
    </div>
</div>
@endsection
