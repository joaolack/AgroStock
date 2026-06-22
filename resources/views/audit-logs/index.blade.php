@extends('layouts.app')

@section('slot')
@php
    $hasFilters = $filters['user_id'] !== ''
        || $filters['module'] !== ''
        || $filters['action'] !== ''
        || $filters['date_from'] !== ''
        || $filters['date_to'] !== '';
    $displayTimezone = config('app.display_timezone');

    $actionTone = function (string $action): array {
        return match ($action) {
            'created' => ['background:#dcfce7;color:#166534;', 'background:#22c55e;'],
            'updated' => ['background:#fffbeb;color:#92400e;', 'background:#f59e0b;'],
            'deleted' => ['background:#fef2f2;color:#b91c1c;', 'background:#ef4444;'],
            'entry' => ['background:#ecfdf5;color:#047857;', 'background:#10b981;'],
            'exit' => ['background:#fff7ed;color:#c2410c;', 'background:#f97316;'],
            default => ['background:#f1f5f9;color:#475569;', 'background:#94a3b8;'],
        };
    };

    $moduleTone = function (string $module): string {
        return match ($module) {
            'products' => 'background:#eef7ef;color:#2d6a35;',
            'categories' => 'background:#eff6ff;color:#1d4ed8;',
            'suppliers' => 'background:#fff7ed;color:#c2410c;',
            'stock_movements' => 'background:#f8fafc;color:#475569;',
            default => 'background:#f1f5f9;color:#475569;',
        };
    };

    $fieldLabels = [
        'name' => 'Nome',
        'description' => 'Descrição',
        'selling_price' => 'Preço de venda',
        'cost_price' => 'Preço de custo',
        'category_id' => 'Categoria',
        'supplier_id' => 'Fornecedor',
        'stock_quantity' => 'Estoque',
        'minimum_stock' => 'Estoque mínimo',
        'expiration_date' => 'Validade',
        'contact_name' => 'Contato',
        'phone' => 'Telefone',
        'email' => 'E-mail',
        'address' => 'Endereço',
        'city' => 'Cidade',
        'state' => 'Estado',
        'zip_code' => 'CEP',
        'notes' => 'Observações',
        'active' => 'Ativo',
        'type' => 'Tipo',
        'reason' => 'Motivo',
        'quantity' => 'Quantidade movimentada',
        'product_id' => 'Produto',
        'product_batch_id' => 'Lote',
    ];

    $valueLabels = [
        'entry' => 'Entrada',
        'exit' => 'Saída',
        'manual' => 'Manual',
        'expired' => 'Vencimento',
    ];

    $formatAuditValue = function ($value) use ($valueLabels) {
        if (is_array($value)) {
            return json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }

        if (is_bool($value)) {
            return $value ? 'Sim' : 'Não';
        }

        if ($value === null || $value === '') {
            return '-';
        }

        return $valueLabels[$value] ?? $value;
    };

    $changedFields = function ($log) {
        $oldValues = $log->old_values ?? [];
        $newValues = $log->new_values ?? [];
        $hiddenFields = $log->module === 'stock_movements'
            ? ['product_id', 'product_batch_id']
            : [];

        return collect(array_unique(array_merge(array_keys($oldValues), array_keys($newValues))))
            ->reject(fn ($field) => in_array($field, $hiddenFields, true))
            ->values();
    };
@endphp

<div class="flex min-h-screen flex-1 flex-col overflow-hidden">
    <header class="sticky top-0 z-20 flex items-center justify-between border-b bg-white/80 px-5 py-3.5 backdrop-blur-md sm:px-6"
        style="border-color:#d4e8d6;">
        <div class="flex min-w-0 items-center gap-3">
            <x-mobile-menu-button />
            <div class="min-w-0">
                <h1 class="truncate font-display text-xl font-bold tracking-tight" style="color:#1a3d1f;">Histórico de atividades</h1>
                <p class="truncate text-[11px]" style="color:#8a9e8c;">Auditoria das principais ações realizadas no AgroStock</p>
            </div>
        </div>

        <div class="flex h-8 w-8 items-center justify-center rounded-full text-sm font-bold text-white"
            style="background:linear-gradient(135deg,#4caf50,#2d6a35);">
            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
        </div>
    </header>

    <div class="flex-1 space-y-5 overflow-y-auto p-4 sm:p-6">
        <section class="overflow-hidden rounded-2xl border bg-white shadow-sm"
            style="border-color:#d4e8d6;box-shadow:0 18px 45px rgba(26,61,31,0.06);">
            <div class="border-b px-5 py-5 sm:px-6"
                style="border-color:#d4e8d6;background:linear-gradient(135deg,#ffffff 0%,#f6fbf4 100%);">
                <div>
                    <h2 class="mt-3 font-display text-xl font-bold tracking-tight sm:text-2xl" style="color:#142f18;">
                        Filtrar registros
                    </h2>
                    <p class="mt-1 text-sm" style="color:#6e876f;">
                        Refine o histórico por usuário, módulo, ação e período.
                    </p>
                </div>
            </div>

            <form method="GET" action="{{ route('audit-logs.index') }}" class="px-5 py-4 sm:px-6">
                <div class="grid grid-cols-1 gap-3 lg:grid-cols-[minmax(180px,0.8fr)_minmax(170px,0.7fr)_minmax(170px,0.7fr)_minmax(150px,0.6fr)_minmax(150px,0.6fr)]">
                    <div>
                        <label for="user_id" class="mb-1.5 block text-xs font-bold uppercase tracking-[0.14em]" style="color:#4a5c4c;">Usuário</label>
                        <select id="user_id" name="user_id"
                            class="h-11 w-full rounded-xl border px-3 text-sm transition-all focus:border-green-600 focus:ring-green-600"
                            style="border-color:#d4e8d6;background:#f9f6f0;color:#1a3d1f;">
                            <option value="">Todos</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" @selected((string) $filters['user_id'] === (string) $user->id)>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="module" class="mb-1.5 block text-xs font-bold uppercase tracking-[0.14em]" style="color:#4a5c4c;">Módulo</label>
                        <select id="module" name="module"
                            class="h-11 w-full rounded-xl border px-3 text-sm transition-all focus:border-green-600 focus:ring-green-600"
                            style="border-color:#d4e8d6;background:#f9f6f0;color:#1a3d1f;">
                            <option value="">Todos</option>
                            @foreach($modules as $module => $label)
                                <option value="{{ $module }}" @selected($filters['module'] === $module)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="action" class="mb-1.5 block text-xs font-bold uppercase tracking-[0.14em]" style="color:#4a5c4c;">Ação</label>
                        <select id="action" name="action"
                            class="h-11 w-full rounded-xl border px-3 text-sm transition-all focus:border-green-600 focus:ring-green-600"
                            style="border-color:#d4e8d6;background:#f9f6f0;color:#1a3d1f;">
                            <option value="">Todas</option>
                            @foreach($actions as $action => $label)
                                <option value="{{ $action }}" @selected($filters['action'] === $action)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="date_from" class="mb-1.5 block text-xs font-bold uppercase tracking-[0.14em]" style="color:#4a5c4c;">Data inicial</label>
                        <input type="date" id="date_from" name="date_from" value="{{ $filters['date_from'] }}"
                            class="h-11 w-full rounded-xl border px-3 text-sm transition-all focus:border-green-600 focus:ring-green-600"
                            style="border-color:#d4e8d6;background:#f9f6f0;color:#1a3d1f;">
                    </div>

                    <div>
                        <label for="date_to" class="mb-1.5 block text-xs font-bold uppercase tracking-[0.14em]" style="color:#4a5c4c;">Data final</label>
                        <input type="date" id="date_to" name="date_to" value="{{ $filters['date_to'] }}"
                            class="h-11 w-full rounded-xl border px-3 text-sm transition-all focus:border-green-600 focus:ring-green-600"
                            style="border-color:#d4e8d6;background:#f9f6f0;color:#1a3d1f;">
                    </div>
                </div>

                <div class="mt-4 flex flex-col gap-2 sm:flex-row sm:items-center">
                    <button type="submit"
                        class="inline-flex h-11 items-center justify-center gap-2 rounded-xl px-4 text-sm font-bold text-white transition-all duration-200 hover:-translate-y-px"
                        style="background:#2d6a35;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.3-4.3M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15Z"/>
                        </svg>
                        Filtrar
                    </button>
                    <a href="{{ route('audit-logs.index') }}"
                        class="inline-flex h-11 items-center justify-center gap-2 rounded-xl border px-4 text-sm font-bold transition-all duration-200 hover:bg-red-50"
                        style="border-color:#fecaca;color:#991b1b;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 6 6 18M6 6l12 12"/>
                        </svg>
                        Limpar
                    </a>
                </div>

                @if ($hasFilters)
                    <div class="mt-4 rounded-xl border px-3 py-2 text-xs" style="border-color:#d4e8d6;background:#f9f6f0;color:#4a5c4c;">
                        <span class="font-bold">Filtros ativos:</span>
                        @if ($filters['user_id'] !== '')
                            <span class="ml-1 font-semibold">usuário selecionado</span>
                        @endif
                        @if ($filters['module'] !== '')
                            <span class="ml-1 font-semibold">módulo "{{ $modules[$filters['module']] ?? $filters['module'] }}"</span>
                        @endif
                        @if ($filters['action'] !== '')
                            <span class="ml-1 font-semibold">ação "{{ $actions[$filters['action']] ?? $filters['action'] }}"</span>
                        @endif
                        @if ($filters['date_from'] !== '')
                            <span class="ml-1 font-semibold">início {{ \Carbon\Carbon::parse($filters['date_from'])->format('d/m/Y') }}</span>
                        @endif
                        @if ($filters['date_to'] !== '')
                            <span class="ml-1 font-semibold">fim {{ \Carbon\Carbon::parse($filters['date_to'])->format('d/m/Y') }}</span>
                        @endif
                    </div>
                @endif
            </form>
        </section>

        <section class="overflow-hidden rounded-2xl border bg-white shadow-sm"
            style="border-color:#d4e8d6;">
            <div class="flex flex-col gap-1 border-b px-5 py-4 sm:flex-row sm:items-center sm:justify-between"
                style="border-color:#d4e8d6;">
                <div>
                    <h2 class="font-display text-base font-bold" style="color:#1a3d1f;">Registros de auditoria</h2>
                    <p class="text-xs" style="color:#8a9e8c;">
                        {{ $auditLogs->total() }} {{ $auditLogs->total() === 1 ? 'registro encontrado' : 'registros encontrados' }}
                    </p>
                </div>
            </div>

            @if ($auditLogs->isEmpty())
                <div class="px-5 py-12 text-center">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl"
                        style="background:#eaf6e9;color:#2d6a35;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v18"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 5H9.5a3.5 3.5 0 0 0 0 7H14a3.5 3.5 0 0 1 0 7H6"/>
                        </svg>
                    </div>
                    <h3 class="mt-4 font-display text-xl font-bold" style="color:#1a3d1f;">Nenhum registro encontrado</h3>
                    <p class="mx-auto mt-2 max-w-md text-sm leading-6" style="color:#6e876f;">
                        @if ($hasFilters)
                            Ajuste os filtros ou limpe a busca para visualizar outros eventos.
                        @else
                            As ações auditadas do sistema aparecerão aqui quando forem registradas.
                        @endif
                    </p>
                    @if ($hasFilters)
                        <a href="{{ route('audit-logs.index') }}"
                            class="mt-6 inline-flex items-center justify-center rounded-xl px-4 py-2.5 text-sm font-bold text-white"
                            style="background:#1a3d1f;">
                            Limpar filtros
                        </a>
                    @endif
                </div>
            @else
                <div class="hidden overflow-x-auto xl:block">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b" style="border-color:#d4e8d6;background:#f9f6f0;">
                                <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-[0.16em]" style="color:#8a9e8c;">Data/Hora</th>
                                <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-[0.16em]" style="color:#8a9e8c;">Usuário</th>
                                <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-[0.16em]" style="color:#8a9e8c;">Módulo</th>
                                <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-[0.16em]" style="color:#8a9e8c;">Ação</th>
                                <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-[0.16em]" style="color:#8a9e8c;">Descrição</th>
                                <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-[0.16em]" style="color:#8a9e8c;">Alterações</th>
                                <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-[0.16em]" style="color:#8a9e8c;">IP</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y" style="divide-color:#eef7ef;">
                            @foreach($auditLogs as $log)
                                @php
                                    $tone = $actionTone($log->action);
                                    $fields = $changedFields($log);
                                    $oldValues = $log->old_values ?? [];
                                    $newValues = $log->new_values ?? [];
                                @endphp
                                <tr class="transition-colors hover:bg-[#fbfdfb]">
                                    <td class="whitespace-nowrap px-5 py-4" style="color:#4a5c4c;">
                                        <p class="font-semibold">{{ $log->created_at->timezone($displayTimezone)->format('d/m/Y') }}</p>
                                        <p class="text-xs" style="color:#8a9e8c;">{{ $log->created_at->timezone($displayTimezone)->format('H:i') }}</p>
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4">
                                        <p class="font-bold" style="color:#1a3d1f;">{{ $log->user?->name ?? 'Sistema' }}</p>
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4">
                                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold" style="{{ $moduleTone($log->module) }}">
                                            {{ $modules[$log->module] ?? $log->module }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4">
                                        <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-bold" style="{{ $tone[0] }}">
                                            <span class="h-1.5 w-1.5 rounded-full" style="{{ $tone[1] }}"></span>
                                            {{ $actions[$log->action] ?? $log->action }}
                                        </span>
                                    </td>
                                    <td class="min-w-64 px-5 py-4" style="color:#4a5c4c;">
                                        {{ $log->description ?? '-' }}
                                    </td>
                                    <td class="min-w-[28rem] px-5 py-4 text-xs" style="color:#4a5c4c;">
                                        @if($fields->isNotEmpty())
                                            <details class="max-w-[34rem]">
                                                <summary class="flex cursor-pointer list-none items-center justify-between gap-3 rounded-xl border px-3 py-2 transition-colors hover:bg-[#f6fbf6]"
                                                    style="border-color:#d4e8d6;background:#fbfdfb;">
                                                    <span class="min-w-0">
                                                        <span class="block font-bold" style="color:#1a3d1f;">
                                                            {{ $fields->count() }} {{ $fields->count() === 1 ? 'alteração' : 'alterações' }}
                                                        </span>
                                                        <span class="block truncate text-[11px]" style="color:#8a9e8c;">
                                                            {{ $fieldLabels[$fields->first()] ?? str_replace('_', ' ', ucfirst($fields->first())) }}
                                                            @if($fields->count() > 1)
                                                                +{{ $fields->count() - 1 }}
                                                            @endif
                                                        </span>
                                                    </span>
                                                    <span class="shrink-0 rounded-full px-2.5 py-1 text-[11px] font-bold" style="background:#eaf6e9;color:#2d6a35;">
                                                        Ver
                                                    </span>
                                                </summary>

                                                <div class="mt-2 space-y-2">
                                                @foreach($fields as $field)
                                                    <div class="rounded-xl border px-3 py-2" style="border-color:#edf4ee;background:#fbfdfb;">
                                                        <p class="font-bold" style="color:#1a3d1f;">{{ $fieldLabels[$field] ?? str_replace('_', ' ', ucfirst($field)) }}</p>
                                                        <p class="mt-1">
                                                            <span style="color:#b91c1c;">{{ $formatAuditValue(data_get($oldValues, $field)) }}</span>
                                                            <span class="mx-1" style="color:#8a9e8c;">→</span>
                                                            <span style="color:#166534;">{{ $formatAuditValue(data_get($newValues, $field)) }}</span>
                                                        </p>
                                                    </div>
                                                @endforeach
                                                </div>
                                            </details>
                                        @else
                                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold" style="background:#f8fafc;color:#64748b;">
                                                Sem alterações
                                            </span>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4" style="color:#4a5c4c;">
                                        {{ $log->ip_address ?? '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="grid gap-3 p-4 xl:hidden">
                    @foreach($auditLogs as $log)
                        @php
                            $tone = $actionTone($log->action);
                            $fields = $changedFields($log);
                            $oldValues = $log->old_values ?? [];
                            $newValues = $log->new_values ?? [];
                        @endphp
                        <article class="rounded-2xl border bg-white p-4 shadow-sm" style="border-color:#d4e8d6;">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="text-sm font-bold" style="color:#1a3d1f;">{{ $log->description ?? 'Registro de auditoria' }}</p>
                                    <p class="mt-1 text-xs" style="color:#8a9e8c;">
                                        {{ $log->created_at->timezone($displayTimezone)->format('d/m/Y H:i') }} · {{ $log->user?->name ?? 'Sistema' }}
                                    </p>
                                </div>
                            </div>

                            <div class="mt-3 flex flex-wrap gap-2">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold" style="{{ $moduleTone($log->module) }}">
                                    {{ $modules[$log->module] ?? $log->module }}
                                </span>
                                <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-bold" style="{{ $tone[0] }}">
                                    <span class="h-1.5 w-1.5 rounded-full" style="{{ $tone[1] }}"></span>
                                    {{ $actions[$log->action] ?? $log->action }}
                                </span>
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold" style="background:#f8fafc;color:#475569;">
                                    IP {{ $log->ip_address ?? '-' }}
                                </span>
                            </div>

                            @if($fields->isNotEmpty())
                                <details class="mt-4">
                                    <summary class="flex cursor-pointer list-none items-center justify-between gap-3 rounded-xl border px-3 py-2 text-xs"
                                        style="border-color:#d4e8d6;background:#fbfdfb;">
                                        <span>
                                            <span class="block font-bold" style="color:#1a3d1f;">
                                                {{ $fields->count() }} {{ $fields->count() === 1 ? 'alteração' : 'alterações' }}
                                            </span>
                                            <span class="block" style="color:#8a9e8c;">Ver antes/depois</span>
                                        </span>
                                        <span class="rounded-full px-2.5 py-1 text-[11px] font-bold" style="background:#eaf6e9;color:#2d6a35;">
                                            Ver
                                        </span>
                                    </summary>

                                    <div class="mt-2 space-y-2">
                                    @foreach($fields as $field)
                                        <div class="rounded-xl border px-3 py-2 text-xs" style="border-color:#edf4ee;background:#fbfdfb;color:#4a5c4c;">
                                            <p class="font-bold" style="color:#1a3d1f;">{{ $fieldLabels[$field] ?? str_replace('_', ' ', ucfirst($field)) }}</p>
                                            <p class="mt-1">
                                                <span style="color:#b91c1c;">{{ $formatAuditValue(data_get($oldValues, $field)) }}</span>
                                                <span class="mx-1" style="color:#8a9e8c;">→</span>
                                                <span style="color:#166534;">{{ $formatAuditValue(data_get($newValues, $field)) }}</span>
                                            </p>
                                        </div>
                                    @endforeach
                                    </div>
                                </details>
                            @endif
                        </article>
                    @endforeach
                </div>

                @if($auditLogs->hasPages())
                    <div class="border-t px-5 py-4" style="border-color:#d4e8d6;">
                        {{ $auditLogs->links('vendor.pagination.agro') }}
                    </div>
                @endif
            @endif
        </section>
    </div>
</div>
@endsection
