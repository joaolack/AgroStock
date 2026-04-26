@extends('layouts.app')

@section('slot')
<div class="flex min-h-screen flex-1 flex-col overflow-hidden">
    <header class="sticky top-0 z-20 flex items-center justify-between border-b bg-white/80 px-6 py-3.5 backdrop-blur-md"
        style="border-color:#d4e8d6;">
        <div class="flex items-center gap-3">
            <button class="lg:hidden rounded-lg p-2 transition colors hover:bg-agro-pale" style="color:#4a5c4c;">
                â˜°
            </button>
            <div>
                <h1 class="font-display text-xl font-bold tracking-tight" style="color:#1a3d1f;">Fornecedores</h1>
                <p class="text-[11px]" style="color:#8a9e8c;">Gerencie seus fornecedores</p>
            </div>
        </div>

        <div class="flex items-center gap-2.5">
            <div class="flex h-8 w-8 cursor-pointer items-center justify-center rounded-full text-sm font-bold text-white"
                style="background:linear-gradient(135deg,#4caf50,#2d6a35);">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
        </div>
    </header>

    <div class="flex-1 space-y-5 overflow-y-auto p-6">
        <div class="overflow-hidden rounded-2xl border bg-white animate-fadeIn" style="border-color:#d4e8d6;animation-delay:0.22s;">
            <div class="flex flex-col justify-between gap-3 border-b px-5 py-4 sm:flex-row sm:items-center"
                style="border-color:#d4e8d6;animation-delay:0.22s;">
                <div>
                    <h2 class="font-display text-base font-bold" style="color:#1a3d1f;">Lista de Fornecedores</h2>
                    <p class="text-xs" style="color:#8a9e8c;">{{ $totalSuppliers }} fornecedores cadastrados</p>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <form action="{{ route('suppliers.index') }}" method="GET" class="relative">
                        <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-sm" style="color:#8a9e8c;">ðŸ”</span>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar fornecedor..."
                            class="rounded-xl border pl-8 pr-3 py-2 text-sm transition-all duration-200 focus:ring-4"
                            style="border-color:#d4e8d6;background:#f9f6f0;color:#1a3d1f;"
                            onfocus="this.style.borderColor='#4caf50';this.style.background='#fff';this.style.boxShadow='0 0 0 4px rgba(76,175,80,0.1)'"
                            onblur="this.style.borderColor='#d4e8d6';this.style.background='#f9f6f0';this.style.boxShadow='none'">
                    </form>

                    <a href="{{ route('suppliers.create') }}" class="flex items-center gap-1.5 rounded-xl px-4 py-2 text-sm font-semibold text-white transition-all duration-200 hover:-translate-y-px hover:shadow-lg"
                        style="background:#1a3d1f;"
                        onmouseover="this.style.background='#2d6a35'"
                        onmouseout="this.style.background='#1a3d1f'">
                        <span class="text-base leading-none">+</span>
                        Adicionar Fornecedor
                    </a>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b" style="border-color:#d4e8d6;background:#f9f6f0;">
                            <th class="px-3 py-3 text-left text-[11px] font-semibold uppercase tracking-wider" style="color:#8a9e8c;">Nome</th>
                            <th class="px-3 py-3 text-left text-[11px] font-semibold uppercase tracking-wider" style="color:#8a9e8c;">Contato</th>
                            <th class="px-3 py-3 text-left text-[11px] font-semibold uppercase tracking-wider" style="color:#8a9e8c;">Telefone</th>
                            <th class="px-3 py-3 text-left text-[11px] font-semibold uppercase tracking-wider" style="color:#8a9e8c;">Cidade/UF</th>
                            <th class="px-3 py-3 text-left text-[11px] font-semibold uppercase tracking-wider" style="color:#8a9e8c;">Produtos</th>
                            <th class="px-3 py-3 text-left text-[11px] font-semibold uppercase tracking-wider" style="color:#8a9e8c;">Status</th>
                            <th class="px-3 py-3 text-left text-[11px] font-semibold uppercase tracking-wider" style="color:#8a9e8c;">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y" style="divide-color:#eef7ef;">
                        @forelse ($suppliers as $supplier)
                            <tr class="prod-row animate-fadeIn">
                                <td class="whitespace-nowrap px-3 py-3.5 text-gray-900 dark:text-gray-100">
                                    <p class="font-semibold">{{ $supplier->name }}</p>
                                </td>
                                <td class="whitespace-nowrap px-3 py-3.5 text-gray-900 dark:text-gray-100">
                                    <p class="font-semibold">{{ $supplier->contact_name ?? '-' }}</p>
                                </td>
                                <td class="whitespace-nowrap px-3 py-3.5 text-gray-900 dark:text-gray-100">
                                    <p class="font-semibold">{{ $supplier->phone }}</p>
                                </td>
                                <td class="whitespace-nowrap px-3 py-3.5 text-gray-900 dark:text-gray-100">
                                    <p class="font-semibold">
                                        @if ($supplier->city && $supplier->state)
                                            {{ $supplier->city }}/{{ $supplier->state }}
                                        @else
                                            -
                                        @endif
                                    </p>
                                </td>
                                <td class="whitespace-nowrap px-3 py-3.5 text-gray-900 dark:text-gray-100">
                                    <p class="font-semibold">{{ $supplier->products_count }} produto(s)</p>
                                </td>
                                <td class="whitespace-nowrap px-3 py-3.5 text-gray-900 dark:text-gray-100">
                                    @if ($supplier->active)
                                        <span class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-800">
                                            âœ“ Ativo
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-800">
                                            âœ— Inativo
                                        </span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-3 py-3.5 text-gray-900 dark:text-gray-100">
                                    <button onclick="SupplierModal.show({{ $supplier->id }})"
                                        class="cursor-pointer text-blue-600 transition hover:text-blue-900"
                                        title="Ver detalhes">
                                        ðŸ‘ï¸
                                    </button>

                                    <a href="{{ route('suppliers.edit', $supplier->id) }}"
                                        class="mr-3 text-indigo-600 transition duration-150 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                        Editar
                                    </a>

                                    <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" class="inline-block"
                                        onsubmit="return confirm('Tem certeza que deseja excluir este fornecedor?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-red-600 transition duration-150 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                            Excluir
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-3 py-6 text-center text-sm text-gray-500">
                                    Nenhum fornecedor encontrado.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div>
                {{ $suppliers->links('vendor.pagination.agro') }}
            </div>
        </div>
    </div>
</div>

@include('suppliers.partials.show-modal')
@endsection

@push('scripts')
<script src="{{ asset('js/suppliers.js') }}"></script>
@endpush
