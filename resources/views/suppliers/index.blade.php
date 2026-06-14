@extends('layouts.app')

@section('slot')
<div class="flex min-h-screen flex-1 flex-col overflow-hidden">
    <header class="sticky top-0 z-20 flex items-center justify-between border-b bg-white/80 px-5 py-3.5 backdrop-blur-md sm:px-6"
        style="border-color:#d4e8d6;">
        <div class="flex min-w-0 items-center gap-3">
            <x-mobile-menu-button />
            <div class="min-w-0">
                <h1 class="truncate font-display text-xl font-bold tracking-tight" style="color:#1a3d1f;">Fornecedores</h1>
                <p class="truncate text-[11px]" style="color:#8a9e8c;">Gerencie contatos, localização e vínculos de compra</p>
            </div>
        </div>

        <div class="flex items-center gap-2.5">
            <div class="flex h-8 w-8 cursor-pointer items-center justify-center rounded-full text-sm font-bold text-white"
                style="background:linear-gradient(135deg,#4caf50,#2d6a35);">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
        </div>
    </header>

    <div class="flex-1 space-y-5 overflow-y-auto p-4 sm:p-6">
        <section class="overflow-hidden rounded-2xl border bg-white shadow-sm"
            style="border-color:#d4e8d6;box-shadow:0 18px 45px rgba(26,61,31,0.06);">
            <div class="border-b px-5 py-5 sm:px-6"
                style="border-color:#d4e8d6;background:linear-gradient(135deg,#ffffff 0%,#f6fbf4 100%);">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                    <div class="max-w-2xl">
                        <h2 class="mt-3 font-display text-xl font-bold tracking-tight sm:text-2xl" style="color:#142f18;">
                            Lista de fornecedores
                        </h2>
                        <p class="mt-1 text-sm" style="color:#6e876f;">
                            {{ $suppliers->total() }} {{ $suppliers->total() === 1 ? 'fornecedor encontrado' : 'fornecedores encontrados' }}
                            @if (request('search'))
                                para "{{ request('search') }}"
                            @else
                                de {{ $totalSuppliers }} cadastrados
                            @endif
                        </p>
                    </div>

                    <a href="{{ route('suppliers.create') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-sm font-bold text-white shadow-sm transition-all duration-200 hover:-translate-y-px hover:shadow-lg"
                        style="background:#1a3d1f;"
                        onmouseover="this.style.background='#2d6a35'"
                        onmouseout="this.style.background='#1a3d1f'">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/>
                        </svg>
                        Adicionar fornecedor
                    </a>
                </div>
            </div>

            <div class="px-5 py-4 sm:px-6">
                <form action="{{ route('suppliers.index') }}" method="GET" class="grid gap-3 lg:grid-cols-[minmax(0,1fr)_auto] lg:items-end">
                    <div>
                        <label for="search" class="mb-1.5 block text-xs font-bold uppercase tracking-[0.14em]" style="color:#4a5c4c;">
                            Buscar fornecedor
                        </label>
                        <div class="relative">
                            <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2" style="color:#8a9e8c;">
                                <x-fas-magnifying-glass class="h-3.5 w-auto"/>
                            </span>
                            <input
                                type="text"
                                id="search"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Nome, contato, e-mail ou cidade"
                                class="h-11 w-full rounded-xl border py-2 pl-9 pr-3 text-sm transition-all duration-200 focus:ring-4"
                                style="border-color:#d4e8d6;background:#f9f6f0;color:#1a3d1f;"
                                onfocus="this.style.borderColor='#4caf50';this.style.background='#fff';this.style.boxShadow='0 0 0 4px rgba(76,175,80,0.1)'"
                                onblur="this.style.borderColor='#d4e8d6';this.style.background='#f9f6f0';this.style.boxShadow='none'"
                            >
                        </div>
                    </div>

                    <div class="flex flex-col gap-2 sm:flex-row">
                        <button type="submit"
                            class="inline-flex h-11 items-center justify-center gap-2 rounded-xl px-4 text-sm font-bold text-white transition-all duration-200 hover:-translate-y-px"
                            style="background:#2d6a35;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.3-4.3M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15Z"/>
                            </svg>
                            Buscar
                        </button>

                        @if (request('search'))
                            <a href="{{ route('suppliers.index') }}"
                                class="inline-flex h-11 items-center justify-center gap-2 rounded-xl border px-4 text-sm font-bold transition-all duration-200 hover:bg-red-50"
                                style="border-color:#fecaca;color:#991b1b;">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 6 6 18M6 6l12 12"/>
                                </svg>
                                Limpar
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </section>

        @if ($suppliers->isEmpty())
            <section class="rounded-2xl border bg-white px-5 py-12 text-center shadow-sm sm:px-6"
                style="border-color:#d4e8d6;">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl"
                    style="background:#eaf6e9;color:#2d6a35;">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 18H9M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"/>
                        <circle cx="17" cy="18" r="2"/>
                        <circle cx="7" cy="18" r="2"/>
                    </svg>
                </div>

                <h2 class="mt-4 font-display text-xl font-bold" style="color:#1a3d1f;">
                    @if (request('search'))
                        Nenhum fornecedor encontrado
                    @else
                        Cadastre seu primeiro fornecedor
                    @endif
                </h2>
                <p class="mx-auto mt-2 max-w-md text-sm leading-6" style="color:#6e876f;">
                    @if (request('search'))
                        Tente ajustar o termo de busca ou limpar o filtro para ver todos os fornecedores.
                    @else
                        Fornecedores conectam produtos, compras, lotes e contatos operacionais do estoque.
                    @endif
                </p>

                <div class="mt-6 flex flex-col justify-center gap-2 sm:flex-row">
                    @if (request('search'))
                        <a href="{{ route('suppliers.index') }}"
                            class="inline-flex items-center justify-center gap-2 rounded-xl border px-4 py-2.5 text-sm font-bold transition-all duration-200 hover:bg-agro-pale"
                            style="border-color:#d4e8d6;color:#4a5c4c;">
                            Limpar busca
                        </a>
                    @endif
                    <a href="{{ route('suppliers.create') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-sm font-bold text-white transition-all duration-200 hover:-translate-y-px"
                        style="background:#1a3d1f;">
                        Cadastrar fornecedor
                    </a>
                </div>
            </section>
        @else
            <section class="hidden overflow-hidden rounded-2xl border bg-white shadow-sm lg:block"
                style="border-color:#d4e8d6;">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b" style="border-color:#d4e8d6;background:#f9f6f0;">
                                <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-[0.16em]" style="color:#8a9e8c;">Fornecedor</th>
                                <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-[0.16em]" style="color:#8a9e8c;">Contato</th>
                                <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-[0.16em]" style="color:#8a9e8c;">Telefone</th>
                                <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-[0.16em]" style="color:#8a9e8c;">Cidade/UF</th>
                                <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-[0.16em]" style="color:#8a9e8c;">Produtos</th>
                                <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-[0.16em]" style="color:#8a9e8c;">Status</th>
                                <th class="px-5 py-3 text-right text-[11px] font-bold uppercase tracking-[0.16em]" style="color:#8a9e8c;">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y" style="divide-color:#eef7ef;">
                            @foreach ($suppliers as $supplier)
                                <tr class="animate-fadeIn transition-colors hover:bg-[#fbfdfb]">
                                    <td class="min-w-56 px-5 py-4 text-gray-900">
                                        <div class="flex items-center gap-3">
                                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl"
                                                style="background:#eaf6e9;color:#2d6a35;">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 18H9M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"/>
                                                </svg>
                                            </span>
                                            <div class="min-w-0">
                                                <p class="font-bold" style="color:#1a3d1f;">{{ $supplier->name }}</p>
                                                <p class="mt-0.5 max-w-52 truncate text-xs" style="color:#8a9e8c;">{{ $supplier->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4 text-gray-900">
                                        <p class="font-semibold" style="color:#4a5c4c;">{{ $supplier->contact_name ?? '-' }}</p>
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4 text-gray-900">
                                        <p class="font-semibold" style="color:#4a5c4c;">{{ $supplier->phone }}</p>
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4 text-gray-900">
                                        <p class="font-semibold" style="color:#4a5c4c;">
                                            @if ($supplier->city && $supplier->state)
                                                {{ $supplier->city }}/{{ $supplier->state }}
                                            @else
                                                -
                                            @endif
                                        </p>
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4">
                                        <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-bold"
                                            style="background:#eef7ef;color:#2d6a35;">
                                            {{ $supplier->products_count }} {{ $supplier->products_count === 1 ? 'produto' : 'produtos' }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4">
                                        @if ($supplier->active)
                                            <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-bold"
                                                style="background:#dcfce7;color:#166534;">
                                                <span class="h-1.5 w-1.5 rounded-full" style="background:#22c55e;"></span>
                                                Ativo
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-bold"
                                                style="background:#f1f5f9;color:#475569;">
                                                <span class="h-1.5 w-1.5 rounded-full" style="background:#94a3b8;"></span>
                                                Inativo
                                            </span>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4 text-right">
                                        <div class="flex justify-end gap-2">
                                            <button type="button" onclick="SupplierModal.show({{ $supplier->id }})"
                                                class="inline-flex h-9 w-9 items-center justify-center rounded-xl border transition-all duration-200 hover:-translate-y-px hover:bg-blue-50"
                                                style="border-color:#bfdbfe;color:#1d4ed8;"
                                                title="Ver detalhes"
                                                aria-label="Ver detalhes do fornecedor {{ $supplier->name }}">
                                                <x-fas-eye class="h-4 w-4" />
                                            </button>

                                            <a href="{{ route('suppliers.edit', $supplier->id) }}"
                                                class="inline-flex h-9 w-9 items-center justify-center rounded-xl border transition-all duration-200 hover:-translate-y-px hover:bg-green-50"
                                                style="border-color:#d4e8d6;color:#2d6a35;"
                                                title="Editar fornecedor"
                                                aria-label="Editar fornecedor {{ $supplier->name }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 7.125 16.862 4.487"/>
                                                </svg>
                                            </a>

                                            @if ($supplier->products_count > 0)
                                                <div x-data="{ open: false }" class="inline-block">
                                                    <button type="button" @click="open = true"
                                                        class="inline-flex h-9 w-9 items-center justify-center rounded-xl border transition-all duration-200 hover:-translate-y-px hover:bg-orange-50"
                                                        style="border-color:#fed7aa;color:#c2410c;"
                                                        title="Ver impedimento de exclusão"
                                                        aria-label="Ver impedimento de exclusão do fornecedor {{ $supplier->name }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z"/>
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01"/>
                                                        </svg>
                                                    </button>

                                                    @include('suppliers.partials.delete-blocked-modal', ['supplier' => $supplier])
                                                </div>
                                            @else
                                                <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" class="inline-block"
                                                    onsubmit="return confirm('Tem certeza que deseja excluir este fornecedor?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="inline-flex h-9 w-9 items-center justify-center rounded-xl border transition-all duration-200 hover:-translate-y-px hover:bg-red-50"
                                                        style="border-color:#fecaca;color:#dc2626;"
                                                        title="Excluir fornecedor"
                                                        aria-label="Excluir fornecedor {{ $supplier->name }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18"/>
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 6V4h8v2"/>
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 6l-1 14H6L5 6"/>
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>

            <div class="grid gap-3 lg:hidden">
                @foreach ($suppliers as $supplier)
                    <article class="rounded-2xl border bg-white p-4 shadow-sm" style="border-color:#d4e8d6;">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <div class="flex items-center gap-2">
                                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl"
                                        style="background:#eaf6e9;color:#2d6a35;">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 18H9M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"/>
                                        </svg>
                                    </span>
                                    <div class="min-w-0">
                                        <h2 class="break-words text-base font-bold" style="color:#1a3d1f;">{{ $supplier->name }}</h2>
                                        <p class="truncate text-xs" style="color:#8a9e8c;">{{ $supplier->email }}</p>
                                    </div>
                                </div>

                                <div class="mt-3 grid gap-2 text-sm" style="color:#4a5c4c;">
                                    <p><span class="font-bold">Contato:</span> {{ $supplier->contact_name ?? '-' }}</p>
                                    <p><span class="font-bold">Telefone:</span> {{ $supplier->phone }}</p>
                                    <p>
                                        <span class="font-bold">Cidade/UF:</span>
                                        @if ($supplier->city && $supplier->state)
                                            {{ $supplier->city }}/{{ $supplier->state }}
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 flex flex-wrap items-center justify-between gap-3 border-t pt-3" style="border-color:#edf4ee;">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-bold"
                                    style="background:#eef7ef;color:#2d6a35;">
                                    {{ $supplier->products_count }} {{ $supplier->products_count === 1 ? 'produto' : 'produtos' }}
                                </span>

                                @if ($supplier->active)
                                    <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-bold"
                                        style="background:#dcfce7;color:#166534;">
                                        <span class="h-1.5 w-1.5 rounded-full" style="background:#22c55e;"></span>
                                        Ativo
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-bold"
                                        style="background:#f1f5f9;color:#475569;">
                                        <span class="h-1.5 w-1.5 rounded-full" style="background:#94a3b8;"></span>
                                        Inativo
                                    </span>
                                @endif
                            </div>

                            <div class="flex items-center gap-2">
                                <button type="button" onclick="SupplierModal.show({{ $supplier->id }})"
                                    class="inline-flex h-9 w-9 items-center justify-center rounded-xl border transition-all duration-200 hover:bg-blue-50"
                                    style="border-color:#bfdbfe;color:#1d4ed8;"
                                    title="Ver detalhes"
                                    aria-label="Ver detalhes do fornecedor {{ $supplier->name }}">
                                    <x-fas-eye class="h-4 w-4" />
                                </button>

                                <a href="{{ route('suppliers.edit', $supplier->id) }}"
                                    class="inline-flex h-9 w-9 items-center justify-center rounded-xl border transition-all duration-200 hover:bg-green-50"
                                    style="border-color:#d4e8d6;color:#2d6a35;"
                                    title="Editar fornecedor"
                                    aria-label="Editar fornecedor {{ $supplier->name }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 7.125 16.862 4.487"/>
                                    </svg>
                                </a>

                                @if ($supplier->products_count > 0)
                                    <div x-data="{ open: false }" class="inline-block">
                                        <button type="button" @click="open = true"
                                            class="inline-flex h-9 w-9 items-center justify-center rounded-xl border transition-all duration-200 hover:bg-orange-50"
                                            style="border-color:#fed7aa;color:#c2410c;"
                                            title="Ver impedimento de exclusão"
                                            aria-label="Ver impedimento de exclusão do fornecedor {{ $supplier->name }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01"/>
                                            </svg>
                                        </button>

                                        @include('suppliers.partials.delete-blocked-modal', ['supplier' => $supplier])
                                    </div>
                                @else
                                    <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" class="inline-block"
                                        onsubmit="return confirm('Tem certeza que deseja excluir este fornecedor?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex h-9 w-9 items-center justify-center rounded-xl border transition-all duration-200 hover:bg-red-50"
                                            style="border-color:#fecaca;color:#dc2626;"
                                            title="Excluir fornecedor"
                                            aria-label="Excluir fornecedor {{ $supplier->name }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 6V4h8v2"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 6l-1 14H6L5 6"/>
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="overflow-hidden rounded-2xl border bg-white shadow-sm" style="border-color:#d4e8d6;">
                {{ $suppliers->links('vendor.pagination.agro') }}
            </div>
        @endif
    </div>
</div>

@include('suppliers.partials.show-modal')
@endsection

@push('scripts')
<script src="{{ asset('js/suppliers.js') }}"></script>
@endpush
