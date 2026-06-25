<section class="hidden overflow-hidden rounded-2xl border bg-white shadow-sm xl:block"
    style="border-color:#d4e8d6;">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b" style="border-color:#d4e8d6;background:#f9f6f0;">
                    <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-[0.16em]" style="color:#8a9e8c;">Produto</th>
                    <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-[0.16em]" style="color:#8a9e8c;">Categoria</th>
                    <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-[0.16em]" style="color:#8a9e8c;">Fornecedor</th>
                    <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-[0.16em]" style="color:#8a9e8c;">Lote</th>
                    <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-[0.16em]" style="color:#8a9e8c;">Estoque</th>
                    <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-[0.16em]" style="color:#8a9e8c;">Preço</th>
                    <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-[0.16em]" style="color:#8a9e8c;">Status</th>
                    <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-[0.16em]" style="color:#8a9e8c;">Validade</th>
                    <th class="px-5 py-3 text-right text-[11px] font-bold uppercase tracking-[0.16em]" style="color:#8a9e8c;">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y" style="divide-color:#eef7ef;">
                @foreach ($products as $product)
                    @php
                        $today = \Carbon\Carbon::now()->startOfDay();
                        $stockedBatches = $product->batches->where('quantity', '>', 0);
                        $validBatches = $stockedBatches
                            ->filter(fn ($batch) => ! $batch->expiration_date || \Carbon\Carbon::parse($batch->expiration_date)->startOfDay()->greaterThanOrEqualTo($today))
                            ->sortBy(fn ($batch) => $batch->expiration_date?->format('Y-m-d') ?? '9999-12-31')
                            ->values();
                        $expiredBatches = $stockedBatches
                            ->filter(fn ($batch) => $batch->expiration_date && \Carbon\Carbon::parse($batch->expiration_date)->startOfDay()->lessThan($today))
                            ->values();
                        $expiredQuantity = $expiredBatches->sum('quantity');
                        $primaryBatch = $validBatches->first();
                        $remainingBatchesCount = max($validBatches->count() - 1, 0);
                        $remainingBatchesTooltip = $validBatches->slice(1)
                            ->map(fn ($batch) => $batch->number . ' (' . $batch->quantity . ')')
                            ->implode(' | ');

                        $stockStatus = $product->stock_status;
                        $displayStockQuantity = (int) ($product->available_stock_quantity ?? $product->stock_quantity);
                        $stockTone = match ($stockStatus) {
                            'Em Falta' => ['background:#fef2f2;color:#b91c1c;', 'background:#ef4444;'],
                            'Estoque Baixo' => ['background:#fffbeb;color:#92400e;', 'background:#f59e0b;'],
                            default => ['background:#dcfce7;color:#166534;', 'background:#22c55e;'],
                        };

                        $expiration = $primaryBatch?->expiration_date;
                        $expirationLabel = $primaryBatch ? 'Sem validade' : 'Sem lote válido';
                        $expirationStyle = $primaryBatch ? 'color:#64748b;' : 'color:#b91c1c;font-weight:700;';

                        if ($expiration) {
                            $expiryDate = \Carbon\Carbon::parse($expiration)->startOfDay();
                            $statusDays = $today->diffInDays($expiryDate, false);
                            $absoluteDays = abs($statusDays);

                            if ($statusDays < 0) {
                                $expirationLabel = "Expirado há {$absoluteDays} dias";
                                $expirationStyle = 'color:#b91c1c;font-weight:700;';
                            } elseif ($statusDays === 0) {
                                $expirationLabel = 'Vence hoje';
                                $expirationStyle = 'color:#b91c1c;font-weight:700;';
                            } elseif ($statusDays <= 60) {
                                $expirationLabel = "Vence em {$absoluteDays} dias";
                                $expirationStyle = 'color:#92400e;font-weight:700;';
                            } else {
                                $expirationLabel = $expiryDate->format('d/m/Y');
                            }
                        }
                    @endphp

                    <tr class="animate-fadeIn transition-colors hover:bg-[#fbfdfb]">
                        <td class="min-w-56 px-5 py-4">
                            <div class="flex items-center gap-3">
                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl"
                                    style="background:#eaf6e9;color:#2d6a35;">
                                    <x-fas-box class="h-4 w-4"/>
                                </span>
                                <div class="min-w-0">
                                    <p class="font-bold" style="color:#1a3d1f;">{{ $product->name }}</p>
                                    <p class="mt-0.5 max-w-56 truncate text-xs" style="color:#8a9e8c;">{{ $product->description }}</p>
                                </div>
                            </div>
                        </td>

                        <td class="whitespace-nowrap px-5 py-4">
                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold" style="background:#eef7ef;color:#2d6a35;">
                                {{ $product->category->name ?? 'Sem categoria' }}
                            </span>
                        </td>

                        <td class="whitespace-nowrap px-5 py-4">
                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold" style="background:#f8fafc;color:#475569;">
                                {{ $product->supplier->name ?? 'Sem fornecedor' }}
                            </span>
                        </td>

                        <td class="whitespace-nowrap px-5 py-4">
                            <div class="space-y-1.5">
                                @if ($primaryBatch)
                                    <div class="flex items-center gap-1.5 text-xs" style="color:#4a5c4c;">
                                        <span class="font-bold">{{ $primaryBatch->number }}</span>
                                        <span>({{ $primaryBatch->quantity }} válidos)</span>

                                        @if ($remainingBatchesCount > 0)
                                            <span class="inline-flex items-center rounded-full border px-2 py-0.5 text-[10px] font-bold"
                                                style="border-color:#d4e8d6;background:#f9f6f0;color:#4a5c4c;"
                                                title="{{ $remainingBatchesTooltip !== '' ? $remainingBatchesTooltip : 'Sem detalhes adicionais' }}">
                                                +{{ $remainingBatchesCount }}
                                            </span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-xs font-bold" style="color:#b91c1c;">Sem lote válido</span>
                                @endif

                                @if ($expiredQuantity > 0)
                                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-bold"
                                        style="background:#fef2f2;color:#b91c1c;">
                                        {{ $expiredQuantity }} vencidos
                                    </span>
                                @endif
                            </div>
                        </td>

                        <td class="whitespace-nowrap px-5 py-4">
                            <p class="text-base font-bold {{ $displayStockQuantity <= $product->minimum_stock ? 'text-red-600' : 'text-green-700' }}">
                                {{ number_format($displayStockQuantity, 0, ',', '.') }}
                            </p>
                            <p class="text-xs" style="color:#8a9e8c;">Válido · mín: {{ number_format($product->minimum_stock, 0, ',', '.') }}</p>
                        </td>

                        <td class="whitespace-nowrap px-5 py-4">
                            <p class="font-bold" style="color:#1a3d1f;">R$ {{ number_format($product->selling_price, 2, ',', '.') }}</p>
                        </td>

                        <td class="whitespace-nowrap px-5 py-4">
                            <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-bold" style="{{ $stockTone[0] }}">
                                {{ $stockStatus }}
                            </span>
                        </td>

                        <td class="whitespace-nowrap px-5 py-4">
                            <span class="text-sm" style="{{ $expirationStyle }}">{{ $expirationLabel }}</span>
                        </td>

                        <td class="whitespace-nowrap px-5 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('products.edit', $product->id) }}"
                                    class="inline-flex h-9 w-9 items-center justify-center rounded-xl border transition-all duration-200 hover:-translate-y-px hover:bg-green-50"
                                    style="border-color:#d4e8d6;color:#2d6a35;"
                                    title="Editar produto"
                                    aria-label="Editar produto {{ $product->name }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 7.125 16.862 4.487"/>
                                    </svg>
                                </a>

                                <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline-block"
                                    onsubmit="return confirm('Tem certeza que deseja excluir este produto?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-xl border transition-all duration-200 hover:-translate-y-px hover:bg-red-50"
                                        style="border-color:#fecaca;color:#dc2626;"
                                        title="Excluir produto"
                                        aria-label="Excluir produto {{ $product->name }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 6V4h8v2"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 6l-1 14H6L5 6"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>

<div class="grid gap-3 xl:hidden">
    @foreach ($products as $product)
        @php
            $today = \Carbon\Carbon::now()->startOfDay();
            $stockedBatches = $product->batches->where('quantity', '>', 0);
            $validBatches = $stockedBatches
                ->filter(fn ($batch) => ! $batch->expiration_date || \Carbon\Carbon::parse($batch->expiration_date)->startOfDay()->greaterThanOrEqualTo($today))
                ->sortBy(fn ($batch) => $batch->expiration_date?->format('Y-m-d') ?? '9999-12-31')
                ->values();
            $expiredBatches = $stockedBatches
                ->filter(fn ($batch) => $batch->expiration_date && \Carbon\Carbon::parse($batch->expiration_date)->startOfDay()->lessThan($today))
                ->values();
            $expiredQuantity = $expiredBatches->sum('quantity');
            $primaryBatch = $validBatches->first();
            $remainingBatchesCount = max($validBatches->count() - 1, 0);

            $stockStatus = $product->stock_status;
            $displayStockQuantity = (int) ($product->available_stock_quantity ?? $product->stock_quantity);
            $stockTone = match ($stockStatus) {
                'Em Falta' => ['background:#fef2f2;color:#b91c1c;', 'background:#ef4444;'],
                'Estoque Baixo' => ['background:#fffbeb;color:#92400e;', 'background:#f59e0b;'],
                default => ['background:#dcfce7;color:#166534;', 'background:#22c55e;'],
            };

            $expiration = $primaryBatch?->expiration_date;
            $expirationLabel = $primaryBatch ? 'Sem validade' : 'Sem lote válido';
            $expirationStyle = $primaryBatch ? 'color:#64748b;' : 'color:#b91c1c;font-weight:700;';

            if ($expiration) {
                $expiryDate = \Carbon\Carbon::parse($expiration)->startOfDay();
                $statusDays = $today->diffInDays($expiryDate, false);
                $absoluteDays = abs($statusDays);

                if ($statusDays < 0) {
                    $expirationLabel = "Expirado há {$absoluteDays} dias";
                    $expirationStyle = 'color:#b91c1c;font-weight:700;';
                } elseif ($statusDays === 0) {
                    $expirationLabel = 'Vence hoje';
                    $expirationStyle = 'color:#b91c1c;font-weight:700;';
                } elseif ($statusDays <= 60) {
                    $expirationLabel = "Vence em {$absoluteDays} dias";
                    $expirationStyle = 'color:#92400e;font-weight:700;';
                } else {
                    $expirationLabel = $expiryDate->format('d/m/Y');
                }
            }
        @endphp

        <article class="rounded-2xl border bg-white p-4 shadow-sm" style="border-color:#d4e8d6;">
            <div class="flex items-start justify-between gap-3">
                <div class="min-w-0">
                    <div class="flex items-center gap-2">
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl"
                            style="background:#eaf6e9;color:#2d6a35;">
                            <x-fas-box class="h-4 w-4"/>
                        </span>
                        <div class="min-w-0">
                            <h2 class="break-words text-base font-bold" style="color:#1a3d1f;">{{ $product->name }}</h2>
                            <p class="mt-0.5 line-clamp-2 text-xs" style="color:#8a9e8c;">{{ $product->description }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4 grid grid-cols-2 gap-3 text-sm">
                <div class="rounded-xl border px-3 py-2" style="border-color:#edf4ee;background:#fbfdfb;">
                    <p class="text-[10px] font-bold uppercase tracking-[0.14em]" style="color:#8a9e8c;">Estoque</p>
                    <p class="mt-1 font-bold {{ $displayStockQuantity <= $product->minimum_stock ? 'text-red-600' : 'text-green-700' }}">
                        {{ number_format($displayStockQuantity, 0, ',', '.') }}
                    </p>
                    <p class="text-xs" style="color:#8a9e8c;">Válido · mín: {{ number_format($product->minimum_stock, 0, ',', '.') }}</p>
                </div>

                <div class="rounded-xl border px-3 py-2" style="border-color:#edf4ee;background:#fbfdfb;">
                    <p class="text-[10px] font-bold uppercase tracking-[0.14em]" style="color:#8a9e8c;">Preço</p>
                    <p class="mt-1 font-bold" style="color:#1a3d1f;">R$ {{ number_format($product->selling_price, 2, ',', '.') }}</p>
                </div>
            </div>

            <div class="mt-3 flex flex-wrap gap-2">
                <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold" style="background:#eef7ef;color:#2d6a35;">
                    {{ $product->category->name ?? 'Sem categoria' }}
                </span>
                <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold" style="background:#f8fafc;color:#475569;">
                    {{ $product->supplier->name ?? 'Sem fornecedor' }}
                </span>
                <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-bold" style="{{ $stockTone[0] }}">
                    <span class="h-1.5 w-1.5 rounded-full" style="{{ $stockTone[1] }}"></span>
                    {{ $stockStatus }}
                </span>
            </div>

            <div class="mt-4 grid gap-2 text-sm" style="color:#4a5c4c;">
                <p>
                    <span class="font-bold">Lote:</span>
                    @if ($primaryBatch)
                        {{ $primaryBatch->number }} ({{ $primaryBatch->quantity }} válidos)
                        @if ($remainingBatchesCount > 0)
                            +{{ $remainingBatchesCount }}
                        @endif
                    @else
                        <span class="font-bold" style="color:#b91c1c;">Sem lote válido</span>
                    @endif

                    @if ($expiredQuantity > 0)
                        <span class="ml-1 inline-flex rounded-full px-2 py-0.5 text-[10px] font-bold"
                            style="background:#fef2f2;color:#b91c1c;">
                            {{ $expiredQuantity }} vencidos
                        </span>
                    @endif
                </p>
                <p>
                    <span class="font-bold">Validade:</span>
                    <span style="{{ $expirationStyle }}">{{ $expirationLabel }}</span>
                </p>
            </div>

            <div class="mt-4 flex items-center justify-end gap-2 border-t pt-3" style="border-color:#edf4ee;">
                <a href="{{ route('products.edit', $product->id) }}"
                    class="inline-flex h-9 w-9 items-center justify-center rounded-xl border transition-all duration-200 hover:bg-green-50"
                    style="border-color:#d4e8d6;color:#2d6a35;"
                    title="Editar produto"
                    aria-label="Editar produto {{ $product->name }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 7.125 16.862 4.487"/>
                    </svg>
                </a>

                <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline-block"
                    onsubmit="return confirm('Tem certeza que deseja excluir este produto?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="inline-flex h-9 w-9 items-center justify-center rounded-xl border transition-all duration-200 hover:bg-red-50"
                        style="border-color:#fecaca;color:#dc2626;"
                        title="Excluir produto"
                        aria-label="Excluir produto {{ $product->name }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 6V4h8v2"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 6l-1 14H6L5 6"/>
                        </svg>
                    </button>
                </form>
            </div>
        </article>
    @endforeach
</div>

<div class="overflow-hidden rounded-2xl border bg-white shadow-sm" style="border-color:#d4e8d6;">
    {{ $products->withQueryString()->links('vendor.pagination.agro') }}
</div>
