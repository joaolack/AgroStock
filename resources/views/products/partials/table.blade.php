<div class="overflow-x-auto">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b" style="border-color:#d4e8d6;background:#f9f6f0;">
                <th class="text-left px-3 py-3 text-[11px] font-semibold uppercase tracking-wider" style="color:#8a9e8c;">Produto</th>
                <th class="text-left px-3 py-3 text-[11px] font-semibold uppercase tracking-wider" style="color:#8a9e8c;">Categoria</th>
                <th class="text-left px-3 py-3 text-[11px] font-semibold uppercase tracking-wider" style="color:#8a9e8c;">Fornecedor</th>
                <th class="text-left px-3 py-3 text-[11px] font-semibold uppercase tracking-wider" style="color:#8a9e8c;">Estoque Atual</th>
                <th class="text-left px-3 py-3 text-[11px] font-semibold uppercase tracking-wider" style="color:#8a9e8c;">Preço Unit.</th>
                <th class="text-left px-3 py-3 text-[11px] font-semibold uppercase tracking-wider" style="color:#8a9e8c;">Status</th>
                <th class="text-left px-3 py-3 text-[11px] font-semibold uppercase tracking-wider" style="color:#8a9e8c;">Validade</th>
                <th class="text-center px-3 py-3 text-[11px] font-semibold uppercase tracking-wider" style="color:#8a9e8c;">Ações</th>
            </tr>
        </thead>
        <tbody id="products-table" class="divide-y" style="divide-color:#eef7ef;">
            @forelse ($products as $product)
                <tr class="prod-row animate-fadeIn">
                    <td class="px-3 py-3.5 whitespace-nowrap text-gray-900 dark:text-gray-100">
                        <p class="font-semibold">{{ $product->name }}</p>
                    </td>

                    <td class="px-3 py-3.5 whitespace-nowrap text-gray-900 dark:text-gray-100">
                        <span class="text-xs font-medium px-2.5 py-1 rounded-full">{{ $product->category->name ?? 'N/A' }}</span>
                    </td>

                    <td class="px-3 py-3.5 whitespace-nowrap text-gray-900 dark:text-gray-100">
                        <span class="text-xs font-medium px-2.5 py-1 rounded-full">{{ $product->supplier->name ?? 'N/A' }}</span>
                    </td>

                    <td class="px-3 py-3.5 whitespace-nowrap text-gray-900 dark:text-gray-100">
                        <span class="font-semibold {{ $product->stock_quantity <= 5 ? 'text-red-600' : 'text-green-600' }}">
                            {{ $product->stock_quantity }}
                        </span>
                        <small class="text-gray-500 block">Min: {{ $product->minimum_stock }}</small>
                    </td>

                    <td class="px-3 py-3.5 font-semibold text-xs whitespace-nowrap text-gray-900 dark:text-gray-100">
                        R$ {{ number_format($product->selling_price, 2, ',', '.') }}
                    </td>

                    <td class="px-6 py-4 text-center whitespace-nowrap">
                        @php
                            $status = $product->stock_status;
                            $class = '';
                            if ($status == 'Em Falta') {
                                $class = 'bg-red-100 text-red-800';
                            } elseif ($status == 'Estoque Baixo') {
                                $class = 'bg-yellow-100 text-yellow-800';
                            } else {
                                $class = 'bg-green-100 text-green-800';
                            }
                        @endphp

                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $class }}">
                            {{ $status }}
                        </span>
                    </td>

                    <td class="px-6 py-4 text-sm text-gray-500 text-center">
                        @if ($product->expiration_date)
                            @php
                                $expiryDate = \Carbon\Carbon::parse($product->expiration_date)->startOfDay();
                                $today = \Carbon\Carbon::now()->startOfDay();
                                $statusDays = $today->diffInDays($expiryDate, false);
                                $absoluteDays = abs($statusDays);
                            @endphp

                            @if ($statusDays < 0)
                                <span class="text-red-500 font-bold">EXPIRADO Há {{ $absoluteDays }} DIAS!</span>
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
                <tr>
                    <td colspan="8" class="text-center py-4">Nenhum produto encontrado</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div>
    {{ $products->withQueryString()->links('vendor.pagination.agro') }}
</div>
