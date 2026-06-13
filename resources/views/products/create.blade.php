@extends('layouts.app')

@section('slot')
<div class="flex min-h-screen flex-1 flex-col overflow-hidden">
    <header class="sticky top-0 z-20 flex items-center justify-between border-b bg-white/85 px-5 py-3.5 backdrop-blur-md sm:px-6"
        style="border-color:#d4e8d6;">
        <div class="flex min-w-0 items-center gap-3">
            <a href="{{ route('products.index') }}"
                class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-xl border bg-white text-sm shadow-sm transition-all duration-200 hover:-translate-x-0.5 hover:bg-green-50"
                style="border-color:#d4e8d6;color:#2d6a35;"
                aria-label="Voltar para produtos">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="h-4 w-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </a>

            <div class="min-w-0">
                <p class="text-[11px] font-bold uppercase tracking-[0.22em]" style="color:#6e876f;">Estoque</p>
                <h1 class="truncate text-xl font-bold tracking-tight sm:text-2xl" style="color:#1a3d1f;">Cadastrar novo produto</h1>
            </div>
        </div>

        <div class="flex items-center gap-2.5">
            <div class="hidden rounded-full border px-3 py-1.5 text-xs font-semibold sm:block"
                style="border-color:#d4e8d6;color:#4a5c4c;background:#f9f6f0;">
                Lote inicial
            </div>
            <div class="flex h-8 w-8 cursor-pointer items-center justify-center rounded-full text-sm font-bold text-white"
                style="background:linear-gradient(135deg,#4caf50,#2d6a35);">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
        </div>
    </header>

    <div class="flex-1 overflow-y-auto p-4 sm:p-6">
        <div class="mx-auto grid max-w-7xl gap-5 xl:grid-cols-[minmax(0,1fr)_360px]">
            <main class="overflow-hidden rounded-2xl border bg-white shadow-sm"
                style="border-color:#d4e8d6;box-shadow:0 18px 45px rgba(26,61,31,0.08);">
                <div class="border-b px-5 py-5 sm:px-6"
                    style="border-color:#d4e8d6;background:linear-gradient(135deg,#ffffff 0%,#f6fbf4 100%);">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                        <div class="max-w-2xl">
                            <div class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-bold uppercase tracking-[0.18em]"
                                style="background:#eaf6e9;color:#2d6a35;">
                                <span class="h-1.5 w-1.5 rounded-full" style="background:#4caf50;"></span>
                                Cadastro
                            </div>
                            <h2 class="mt-4 text-2xl font-bold tracking-tight sm:text-3xl" style="color:#142f18;">
                                Dados comerciais e primeiro saldo
                            </h2>
                            <p class="mt-2 max-w-xl text-sm leading-6" style="color:#6e876f;">
                                Registre o produto, vincule categoria e fornecedor, defina valores e informe o lote que entra no estoque.
                            </p>
                        </div>

                        <div class="grid grid-cols-3 overflow-hidden rounded-xl border bg-white text-center"
                            style="border-color:#d4e8d6;">
                            <div class="px-4 py-3">
                                <p class="text-[10px] font-bold uppercase tracking-[0.16em]" style="color:#8a9e8c;">Etapa</p>
                                <p class="mt-1 text-lg font-bold" style="color:#1a3d1f;">01</p>
                            </div>
                            <div class="border-x px-4 py-3" style="border-color:#d4e8d6;">
                                <p class="text-[10px] font-bold uppercase tracking-[0.16em]" style="color:#8a9e8c;">Fluxo</p>
                                <p class="mt-1 text-lg font-bold" style="color:#1a3d1f;">Entrada</p>
                            </div>
                            <div class="px-4 py-3">
                                <p class="text-[10px] font-bold uppercase tracking-[0.16em]" style="color:#8a9e8c;">Status</p>
                                <p class="mt-1 text-lg font-bold" style="color:#1a3d1f;">Novo</p>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="mx-5 mt-5 rounded-xl border px-4 py-3 sm:mx-6"
                        style="border-color:#fecaca;background:#fff7f7;color:#991b1b;">
                        <div class="flex items-start gap-3">
                            <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg"
                                style="background:#fee2e2;color:#b91c1c;">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="h-4 w-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold">Revise os campos destacados</p>
                                <ul class="mt-1 list-disc space-y-1 pl-4 text-sm">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <form action="{{ route('products.store') }}" method="POST" class="px-5 py-6 sm:px-6">
                    @csrf

                    <section>
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl"
                                style="background:#eaf6e9;color:#2d6a35;">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="h-5 w-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m3.3 7 8.7 5 8.7-5M12 22V12" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base font-bold" style="color:#1a3d1f;">Identificação</h3>
                                <p class="text-xs" style="color:#8a9e8c;">Nome, categoria e descrição do produto</p>
                            </div>
                        </div>

                        <div class="mt-5 grid grid-cols-1 gap-5 lg:grid-cols-2">
                            <div class="lg:col-span-2">
                                <label for="name" class="mb-1.5 block text-xs font-bold uppercase tracking-[0.14em]" style="color:#4a5c4c;">
                                    Nome do produto <span class="text-red-600">*</span>
                                </label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}"
                                    class="block w-full rounded-xl border-gray-300 bg-white text-sm text-slate-900 shadow-sm transition focus:border-green-600 focus:ring-green-600 @error('name') border-red-500 @enderror"
                                    placeholder="Nome do produto" required>
                                @error('name') <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="category_id" class="mb-1.5 block text-xs font-bold uppercase tracking-[0.14em]" style="color:#4a5c4c;">
                                    Categoria <span class="text-red-600">*</span>
                                </label>
                                <select id="category_id" name="category_id"
                                    class="block w-full rounded-xl border-gray-300 bg-white text-sm text-slate-900 shadow-sm transition focus:border-green-600 focus:ring-green-600 @error('category_id') border-red-500 @enderror"
                                    required>
                                    <option value="">Selecione uma categoria</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id') <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="supplier_id" class="mb-1.5 block text-xs font-bold uppercase tracking-[0.14em]" style="color:#4a5c4c;">
                                    Fornecedor do lote inicial
                                </label>
                                <select id="supplier_id" name="supplier_id"
                                    class="block w-full rounded-xl border-gray-300 bg-white text-sm text-slate-900 shadow-sm transition focus:border-green-600 focus:ring-green-600 @error('supplier_id') border-red-500 @enderror">
                                    <option value="">Selecione um fornecedor</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                            {{ $supplier->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('supplier_id') <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div class="lg:col-span-2">
                                <label for="description" class="mb-1.5 block text-xs font-bold uppercase tracking-[0.14em]" style="color:#4a5c4c;">
                                    Descrição do produto <span class="text-red-600">*</span>
                                </label>
                                <textarea id="description" name="description" rows="4"
                                    class="block w-full rounded-xl border-gray-300 bg-white text-sm text-slate-900 shadow-sm transition focus:border-green-600 focus:ring-green-600 @error('description') border-red-500 @enderror"
                                    placeholder="Descreva o produto..." required></textarea>
                                @error('description') <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </section>

                    <section class="mt-8 border-t pt-8" style="border-color:#d4e8d6;">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl"
                                style="background:#fff7ed;color:#c2410c;">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="h-5 w-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7H14a3.5 3.5 0 0 1 0 7H6" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base font-bold" style="color:#1a3d1f;">Valores e estoque</h3>
                                <p class="text-xs" style="color:#8a9e8c;">Preços, quantidade inicial e ponto de reposição</p>
                            </div>
                        </div>

                        <div class="mt-5 grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-4">
                            <div>
                                <label for="cost_price" class="mb-1.5 block text-xs font-bold uppercase tracking-[0.14em]" style="color:#4a5c4c;">
                                    Preço de custo (R$) <span class="text-red-600">*</span>
                                </label>
                                <input type="number" step="0.01" min="0" id="cost_price" value="{{ old('cost_price') }}" name="cost_price"
                                    class="block w-full rounded-xl border-gray-300 bg-white text-sm text-slate-900 shadow-sm transition focus:border-green-600 focus:ring-green-600 @error('cost_price') border-red-500 @enderror"
                                    placeholder="0,00" required>
                                @error('cost_price') <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="selling_price" class="mb-1.5 block text-xs font-bold uppercase tracking-[0.14em]" style="color:#4a5c4c;">
                                    Preço de venda (R$) <span class="text-red-600">*</span>
                                </label>
                                <input type="number" step="0.01" min="0.01" id="selling_price" name="selling_price" value="{{ old('selling_price') }}"
                                    class="block w-full rounded-xl border-gray-300 bg-white text-sm text-slate-900 shadow-sm transition focus:border-green-600 focus:ring-green-600 @error('selling_price') border-red-500 @enderror"
                                    placeholder="0,00" required>
                                @error('selling_price') <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="stock_quantity" class="mb-1.5 block text-xs font-bold uppercase tracking-[0.14em]" style="color:#4a5c4c;">
                                    Quantidade <span class="text-red-600">*</span>
                                </label>
                                <input type="number" min="0" id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity') }}"
                                    class="block w-full rounded-xl border-gray-300 bg-white text-sm text-slate-900 shadow-sm transition focus:border-green-600 focus:ring-green-600 @error('stock_quantity') border-red-500 @enderror"
                                    placeholder="0" required>
                                @error('stock_quantity') <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="minimum_stock" class="mb-1.5 block text-xs font-bold uppercase tracking-[0.14em]" style="color:#4a5c4c;">
                                    Estoque mínimo <span class="text-red-600">*</span>
                                </label>
                                <input type="number" min="0" id="minimum_stock" name="minimum_stock" value="{{ old('minimum_stock') }}"
                                    class="block w-full rounded-xl border-gray-300 bg-white text-sm text-slate-900 shadow-sm transition focus:border-green-600 focus:ring-green-600 @error('minimum_stock') border-red-500 @enderror"
                                    placeholder="0" required>
                                @error('minimum_stock') <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </section>

                    <section class="mt-8 border-t pt-8" style="border-color:#d4e8d6;">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl"
                                style="background:#eff6ff;color:#1d4ed8;">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="h-5 w-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 2v4M16 2v4M3 10h18" />
                                    <rect width="18" height="18" x="3" y="4" rx="2" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base font-bold" style="color:#1a3d1f;">Rastreabilidade do lote</h3>
                                <p class="text-xs" style="color:#8a9e8c;">Número interno e validade do primeiro lote</p>
                            </div>
                        </div>

                        <div class="mt-5 grid grid-cols-1 gap-5 md:grid-cols-2">
                            <div>
                                <label for="batch_number" class="mb-1.5 block text-xs font-bold uppercase tracking-[0.14em]" style="color:#4a5c4c;">
                                    Número do lote inicial
                                </label>
                                <input type="text" id="batch_number" name="batch_number" value="{{ old('batch_number') }}"
                                    class="block w-full rounded-xl border-gray-300 bg-white text-sm text-slate-900 shadow-sm transition focus:border-green-600 focus:ring-green-600 @error('batch_number') border-red-500 @enderror"
                                    placeholder="Ex: LOTE-001">
                                @error('batch_number') <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="expiration_date" class="mb-1.5 block text-xs font-bold uppercase tracking-[0.14em]" style="color:#4a5c4c;">
                                    Validade do lote inicial
                                </label>
                                <input type="date" id="expiration_date" name="expiration_date" value="{{ old('expiration_date') }}"
                                    class="block w-full rounded-xl border-gray-300 bg-white text-sm text-slate-900 shadow-sm transition focus:border-green-600 focus:ring-green-600 @error('expiration_date') border-red-500 @enderror">
                                @error('expiration_date') <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </section>

                    <div class="mt-8 flex flex-col-reverse gap-3 border-t pt-5 sm:flex-row sm:items-center sm:justify-end"
                        style="border-color:#d4e8d6;">
                        <a href="{{ route('products.index') }}"
                            class="inline-flex items-center justify-center gap-2 rounded-xl border px-5 py-3 text-sm font-bold transition-all duration-200 hover:bg-red-50"
                            style="border-color:#fecaca;color:#991b1b;">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 6 6 18M6 6l12 12" />
                            </svg>
                            Cancelar
                        </a>
                        <button type="submit"
                            class="inline-flex items-center justify-center gap-2 rounded-xl px-5 py-3 text-sm font-bold text-white shadow-lg transition-all duration-200 hover:-translate-y-px hover:shadow-xl"
                            style="background:#1a3d1f;box-shadow:0 12px 24px rgba(26,61,31,0.18);"
                            onmouseover="this.style.background='#2d6a35'"
                            onmouseout="this.style.background='#1a3d1f'">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 6 9 17l-5-5" />
                            </svg>
                            Cadastrar produto
                        </button>
                    </div>
                </form>
            </main>

            <aside class="space-y-5">
                <div class="rounded-2xl border bg-white p-5 shadow-sm" style="border-color:#d4e8d6;">
                    <h3 class="text-sm font-bold uppercase tracking-[0.16em]" style="color:#1a3d1f;">Resumo do cadastro</h3>
                    <div class="mt-4 space-y-3">
                        <div class="flex items-center gap-3 rounded-xl border px-3 py-3" style="border-color:#edf4ee;background:#fbfdfb;">
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg" style="background:#eaf6e9;color:#2d6a35;">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="h-4 w-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7 10 17l-5-5" />
                                </svg>
                            </span>
                            <div>
                                <p class="text-sm font-semibold text-slate-900">Produto catalogado</p>
                                <p class="text-xs text-slate-500">Nome, categoria e descrição</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 rounded-xl border px-3 py-3" style="border-color:#edf4ee;background:#fbfdfb;">
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg" style="background:#fff7ed;color:#c2410c;">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="h-4 w-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7H14a3.5 3.5 0 0 1 0 7H6" />
                                </svg>
                            </span>
                            <div>
                                <p class="text-sm font-semibold text-slate-900">Preços definidos</p>
                                <p class="text-xs text-slate-500">Custo, venda e estoque m&iacute;nimo</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 rounded-xl border px-3 py-3" style="border-color:#edf4ee;background:#fbfdfb;">
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg" style="background:#eff6ff;color:#1d4ed8;">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="h-4 w-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 2v4M16 2v4M3 10h18" />
                                    <rect width="18" height="18" x="3" y="4" rx="2" />
                                </svg>
                            </span>
                            <div>
                                <p class="text-sm font-semibold text-slate-900">Lote rastreável</p>
                                <p class="text-xs text-slate-500">Fornecedor, número e validade</p>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</div>
@endsection
