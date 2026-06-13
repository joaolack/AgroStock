@extends('layouts.app')

@section('slot')
<div class="flex-1 flex flex-col min-h-screen overflow-hidden">
    <header class="sticky top-0 z-20 flex items-center justify-between px-6 py-3.5 border-b bg-white/80 backdrop-blur-md"
            style="border-color:#d4e8d6;">
        <div>
            <h1 class="font-display text-xl font-bold tracking-tight" style="color:#1a3d1f;">Histórico de atividades</h1>
            <p class="text-[11px]" style="color:#8a9e8c;">Auditoria das principais ações realizadas no AgroStock</p>
        </div>

        <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold text-white"
             style="background:linear-gradient(135deg,#4caf50,#2d6a35);">
            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
        </div>
    </header>

    <div class="flex-1 p-6 overflow-y-auto space-y-5">
        <form method="GET" action="{{ route('audit-logs.index') }}" class="bg-white rounded-2xl border p-4" style="border-color:#d4e8d6;">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
                <div>
                    <label for="user_id" class="block text-xs font-semibold uppercase tracking-wide mb-1" style="color:#6e876f;">Usuário</label>
                    <select id="user_id" name="user_id" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 text-sm">
                        <option value="">Todos</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" @selected((string) $filters['user_id'] === (string) $user->id)>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="module" class="block text-xs font-semibold uppercase tracking-wide mb-1" style="color:#6e876f;">Modulo</label>
                    <select id="module" name="module" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 text-sm">
                        <option value="">Todos</option>
                        @foreach($modules as $module => $label)
                            <option value="{{ $module }}" @selected($filters['module'] === $module)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="action" class="block text-xs font-semibold uppercase tracking-wide mb-1" style="color:#6e876f;">Ação</label>
                    <select id="action" name="action" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 text-sm">
                        <option value="">Todas</option>
                        @foreach($actions as $action => $label)
                            <option value="{{ $action }}" @selected($filters['action'] === $action)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="date_from" class="block text-xs font-semibold uppercase tracking-wide mb-1" style="color:#6e876f;">Início</label>
                    <input type="date" id="date_from" name="date_from" value="{{ $filters['date_from'] }}"
                           class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 text-sm">
                </div>

                <div>
                    <label for="date_to" class="block text-xs font-semibold uppercase tracking-wide mb-1" style="color:#6e876f;">Fim</label>
                    <input type="date" id="date_to" name="date_to" value="{{ $filters['date_to'] }}"
                           class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 text-sm">
                </div>
            </div>

            <div class="mt-3 flex items-center gap-2">
                <button type="submit" class="px-4 py-2 rounded-lg text-sm font-semibold text-white" style="background:#2d6a35;">
                    Filtrar
                </button>
                <a href="{{ route('audit-logs.index') }}" class="px-4 py-2 rounded-lg text-sm font-semibold border" style="border-color:#d4e8d6;color:#4a5c4c;">
                    Limpar
                </a>
            </div>
        </form>

        <div class="bg-white rounded-2xl border overflow-hidden" style="border-color:#d4e8d6;">
            <div class="flex items-center justify-between px-5 py-4 border-b" style="border-color:#d4e8d6;">
                <div>
                    <h2 class="font-display font-bold text-base" style="color:#1a3d1f;">Registros de auditoria</h2>
                    <p class="text-xs" style="color:#8a9e8c;">
                        {{ $auditLogs->total() }} {{ $auditLogs->total() === 1 ? 'registro encontrado' : 'registros encontrados' }}
                    </p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b" style="border-color:#d4e8d6;background:#f9f6f0;">
                            <th class="text-left px-5 py-3 text-[11px] font-semibold uppercase tracking-wider" style="color:#8a9e8c;">Data/Hora</th>
                            <th class="text-left px-5 py-3 text-[11px] font-semibold uppercase tracking-wider" style="color:#8a9e8c;">Usuário</th>
                            <th class="text-left px-5 py-3 text-[11px] font-semibold uppercase tracking-wider" style="color:#8a9e8c;">Modulo</th>
                            <th class="text-left px-5 py-3 text-[11px] font-semibold uppercase tracking-wider" style="color:#8a9e8c;">Ação</th>
                            <th class="text-left px-5 py-3 text-[11px] font-semibold uppercase tracking-wider" style="color:#8a9e8c;">Descrição</th>
                            <th class="text-left px-5 py-3 text-[11px] font-semibold uppercase tracking-wider" style="color:#8a9e8c;">Alterações</th>
                            <th class="text-left px-5 py-3 text-[11px] font-semibold uppercase tracking-wider" style="color:#8a9e8c;">IP</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($auditLogs as $log)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-5 py-4 whitespace-nowrap text-gray-700">
                                    {{ $log->created_at->timezone('America/Sao_Paulo')->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap text-gray-900">
                                    {{ $log->user?->name ?? 'Sistema' }}
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold" style="background:#eef7ef;color:#1a3d1f;">
                                        {{ $modules[$log->module] ?? $log->module }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap">
                                    @php
                                        $actionClass = match ($log->action) {
                                            'created' => 'bg-green-100 text-green-800',
                                            'updated' => 'bg-yellow-100 text-yellow-800',
                                            'deleted' => 'bg-red-100 text-red-800',
                                            'entry' => 'bg-emerald-100 text-emerald-800',
                                            'exit' => 'bg-orange-100 text-orange-800',
                                            default => 'bg-gray-100 text-gray-800',
                                        };
                                    @endphp
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold {{ $actionClass }}">
                                        {{ $actions[$log->action] ?? $log->action }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 min-w-64 text-gray-700">
                                    {{ $log->description ?? '-' }}
                                </td>
                                <td class="px-5 py-4 min-w-80 text-xs text-gray-700">
                                    @php
                                        $oldValues = $log->old_values ?? [];
                                        $newValues = $log->new_values ?? [];
                                        $hiddenFields = $log->module === 'stock_movements'
                                            ? ['product_id', 'product_batch_id']
                                            : [];
                                        $fieldLabels = [
                                            'name' => 'Nome',
                                            'description' => 'Descricao',
                                            'selling_price' => 'Preco de venda',
                                            'cost_price' => 'Preco de custo',
                                            'category_id' => 'Categoria',
                                            'supplier_id' => 'Fornecedor',
                                            'stock_quantity' => 'Estoque',
                                            'minimum_stock' => 'Estoque mínimo',
                                            'expiration_date' => 'Validade',
                                            'contact_name' => 'Contato',
                                            'phone' => 'Telefone',
                                            'email' => 'E-mail',
                                            'address' => 'Endereco',
                                            'city' => 'Cidade',
                                            'state' => 'Estado',
                                            'zip_code' => 'CEP',
                                            'notes' => 'Observacoes',
                                            'active' => 'Ativo',
                                            'type' => 'Tipo',
                                            'reason' => 'Motivo',
                                            'quantity' => 'Quantidade movimentada',
                                            'product_id' => 'Produto',
                                            'product_batch_id' => 'Lote',
                                        ];
                                        $valueLabels = [
                                            'entry' => 'Entrada',
                                            'exit' => 'Saida',
                                            'manual' => 'Manual',
                                            'expired' => 'Vencimento',
                                        ];
                                        $fields = collect(array_unique(array_merge(array_keys($oldValues), array_keys($newValues))))
                                            ->reject(fn ($field) => in_array($field, $hiddenFields, true))
                                            ->values();
                                        $formatValue = function ($value) use ($valueLabels) {
                                            if (is_array($value)) {
                                                return json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                                            }

                                            if (is_bool($value)) {
                                                return $value ? 'Sim' : 'Nao';
                                            }

                                            if ($value === null || $value === '') {
                                                return '-';
                                            }

                                            return $valueLabels[$value] ?? $value;
                                        };
                                    @endphp

                                    @if($fields->isNotEmpty())
                                        <div class="space-y-1">
                                            @foreach($fields as $field)
                                                <div>
                                                    <span class="font-semibold">{{ $fieldLabels[$field] ?? str_replace('_', ' ', ucfirst($field)) }}:</span>
                                                    <span class="text-red-700">{{ $formatValue(data_get($oldValues, $field)) }}</span>
                                                    <span class="mx-1 text-gray-400">-></span>
                                                    <span class="text-green-700">{{ $formatValue(data_get($newValues, $field)) }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap text-gray-600">
                                    {{ $log->ip_address ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-8 text-center text-gray-500">
                                    Nenhum registro de auditoria encontrado.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($auditLogs->hasPages())
                <div class="px-5 py-4 border-t" style="border-color:#d4e8d6;">
                    {{ $auditLogs->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
