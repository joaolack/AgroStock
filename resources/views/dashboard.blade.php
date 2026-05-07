@extends('layouts.app')

@section('slot')
<main class="flex-1 flex flex-col min-h-screen overflow-hidden">

    <header class="sticky top-0 z-20 flex items-center justify-between px-6 py-3.5 border-b bg-white/80 backdrop-blur-md"
            style="border-color:#d4e8d6;">
        <div class="flex items-center gap-3">
            <button class="lg:hidden p-2 rounded-lg hover:bg-agro-pale transition colors" style="color:#4a5c4c;">
                ☰
            </button>
            <div>
                <h1 class="font-display text-xl font-bold tracking-tight" style="color:#1a3d1f;">Bom dia, {{ auth()->user()->name }}</h1>
                <p class="text-[11px]" style="color:#8a9e8c;">{{ ucfirst(today()->translatedFormat('l, d \d\e F \d\e Y')) }}</p>
            </div>
        </div>
        
        <div class="flex items-center gap-2.5">
            <a href="{{ route('export.index') }}"
                    class="hidden sm:flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-semibold border transition-all hover:bg-agro-pale"
                    style="border-color:#d4e8d6;color:#4a5c4c;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
</svg>
 Exportar relatório
            </a>
            <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold text-white cursor-pointer"
                 style="background:linear-gradient(135deg,#4caf50,#2d6a35);">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            <div>
        </div>
    </header>

    <div class="flex-1 p-6 overflow-y-auto space-y-5">
        <!--Cards-->    
        <div class="grid grid-cols-2 xl:grid-cols-4 gap-4">

            <div class="card bg-white rounded-2xl p-5 border relative overflow-hidden" style="border-color:#d4e8d6;">  
                <div class="flex items-start justify-between mb-4">
                    <div class="w-11 h-11 rounded-xl flex items-center justify-center text-xl" style="background:#eef7ef;">📦</div>
                </div>
                <p class="font-display text-3xl font-bold tracking-tight" style="color:#1a3d1f;"> {{ number_format($totalProducts, 0, ',', '.')}}</p>
                <p class="text-xs mt-1" style="color:#8a9e8c;">Total de Produtos Cadastrados</p>
            </div>

            <div class="card bg-white rounded-2xl p-5 border relative overflow-hidden" style="border-color:#fecaca;">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-11 h-11 rounded-xl flex items-center justify-center text-xl" style="background:#fee2e2;">🚨</div>
                    <span class="text-[10px] font-bold px-2 py-1 rounded-full" style="background:#fee2e2;color:#b91c1c;">Crítico</span>
                </div>
                <p class="font-display text-3xl font-bold tracking-tight" style="color:#1a3d1f;">{{ $criticalStock->count() }}</p>
                <p class="text-xs mt-1" style="color:#8a9e8c;">Total de Alertas</p>
            </div>

            <div class="card bg-white rounded-2xl p-5 border relative overflow-hidden" style="border-color:#d4e8d6;">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-11 h-11 rounded-xl flex items-center justify-center text-xl" style="background:#eef7ef;">🔄</div>
                </div>
                <p class="font-display text-3xl font-bold tracking-tight" style="color:#1a3d1f;">4</p>
                <p class="text-xs mt-1" style="color:#8a9e8c;">Movimentações (hoje)</p>
            </div>

            <div class="card bg-white rounded-2xl p-5 border relative overflow-hidden" style="border-color:#d4e8d6;">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-11 h-11 rounded-xl flex items-center justify-center text-xl" style="background:#eef7ef;">💰</div>
                </div>
                    <p class="font-display text-3xl font-bold tracking-tight" style="color:#1a3d1f;">R$ {{ number_format($totalStockValue, 2, ',', '.')}}</p>
                    <p class="text-xs mt-1" style="color:#8a9e8c;">Valor Total do Estoque</p>      
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
                        @foreach ($closeToExpiry->take(5) as $batch)
                            <li class="py-3 flex justify-between itens-center text-sm">
                                <span class="font-medium text-gray-900">
                                    {{ $batch->product->name }} - Lote {{ $batch->number }}
                                </span>
                                @php
                                    $expiryDate = \Carbon\Carbon::parse($batch->expiration_date)->startOfDay();
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
</main>
