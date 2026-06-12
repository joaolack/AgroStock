@extends('layouts.app')

@section('slot')
<div class="flex min-h-screen flex-1 flex-col overflow-hidden">
    <header class="sticky top-0 z-20 flex items-center justify-between border-b bg-white/85 px-5 py-3.5 backdrop-blur-md sm:px-6"
        style="border-color:#d4e8d6;">
        <div class="flex min-w-0 items-center gap-3">
            <a href="{{ route('categories.index') }}"
                class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-xl border bg-white text-sm shadow-sm transition-all duration-200 hover:-translate-x-0.5 hover:bg-green-50"
                style="border-color:#d4e8d6;color:#2d6a35;"
                aria-label="Voltar para categorias">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="h-4 w-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </a>

            <div class="min-w-0">
                <p class="text-[11px] font-bold uppercase tracking-[0.22em]" style="color:#6e876f;">Cat&aacute;logo</p>
                <h1 class="truncate text-xl font-bold tracking-tight sm:text-2xl" style="color:#1a3d1f;">Cadastrar categoria</h1>
            </div>
        </div>

        <div class="flex items-center gap-2.5">
            <div class="hidden rounded-full border px-3 py-1.5 text-xs font-semibold sm:block"
                style="border-color:#d4e8d6;color:#4a5c4c;background:#f9f6f0;">
                Classifica&ccedil;&atilde;o
            </div>
            <div class="flex h-8 w-8 cursor-pointer items-center justify-center rounded-full text-sm font-bold text-white"
                style="background:linear-gradient(135deg,#4caf50,#2d6a35);">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
        </div>
    </header>

    <div class="flex-1 overflow-y-auto p-4 sm:p-6">
        <div class="mx-auto grid max-w-6xl gap-5 lg:grid-cols-[minmax(0,1fr)_340px]">
            <main class="overflow-hidden rounded-2xl border bg-white shadow-sm"
                style="border-color:#d4e8d6;box-shadow:0 18px 45px rgba(26,61,31,0.08);">
                <div class="border-b px-5 py-5 sm:px-6"
                    style="border-color:#d4e8d6;background:linear-gradient(135deg,#ffffff 0%,#f6fbf4 100%);">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                        <div class="max-w-2xl">
                            <div class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-bold uppercase tracking-[0.18em]"
                                style="background:#eaf6e9;color:#2d6a35;">
                                <span class="h-1.5 w-1.5 rounded-full" style="background:#4caf50;"></span>
                                Nova categoria
                            </div>
                            <h2 class="mt-4 text-2xl font-bold tracking-tight sm:text-3xl" style="color:#142f18;">
                                Organize o estoque por tipo de produto
                            </h2>
                            <p class="mt-2 max-w-xl text-sm leading-6" style="color:#6e876f;">
                                Crie uma categoria clara para agrupar produtos, facilitar filtros e manter o cat&aacute;logo consistente.
                            </p>
                        </div>

                        <div class="grid grid-cols-2 overflow-hidden rounded-xl border bg-white text-center"
                            style="border-color:#d4e8d6;">
                            <div class="px-4 py-3">
                                <p class="text-[10px] font-bold uppercase tracking-[0.16em]" style="color:#8a9e8c;">Campos</p>
                                <p class="mt-1 text-lg font-bold" style="color:#1a3d1f;">02</p>
                            </div>
                            <div class="border-l px-4 py-3" style="border-color:#d4e8d6;">
                                <p class="text-[10px] font-bold uppercase tracking-[0.16em]" style="color:#8a9e8c;">Uso</p>
                                <p class="mt-1 text-lg font-bold" style="color:#1a3d1f;">Produtos</p>
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

                <form action="{{ route('categories.store') }}" method="POST" class="px-5 py-6 sm:px-6">
                    @csrf

                    <section>
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl"
                                style="background:#eaf6e9;color:#2d6a35;">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="h-5 w-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.172 2a2 2 0 0 1 1.414.586l6.71 6.71a2.4 2.4 0 0 1 0 3.408l-4.592 4.592a2.4 2.4 0 0 1-3.408 0l-6.71-6.71A2 2 0 0 1 6 9.172V3a1 1 0 0 1 1-1z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2 7v6.172a2 2 0 0 0 .586 1.414l6.71 6.71a2.4 2.4 0 0 0 3.191.193" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6.5h.01" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base font-bold" style="color:#1a3d1f;">Dados da categoria</h3>
                                <p class="text-xs" style="color:#8a9e8c;">Nome obrigat&oacute;rio e descri&ccedil;&atilde;o opcional</p>
                            </div>
                        </div>

                        <div class="mt-5 space-y-5">
                            <div>
                                <label for="name" class="mb-1.5 block text-xs font-bold uppercase tracking-[0.14em]" style="color:#4a5c4c;">
                                    Nome da categoria <span class="text-red-600">*</span>
                                </label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}"
                                    class="block w-full rounded-xl border-gray-300 bg-white text-sm text-slate-900 shadow-sm transition focus:border-green-600 focus:ring-green-600 @error('name') border-red-500 @enderror"
                                    placeholder="Ex: Ra&ccedil;&otilde;es, Medicamentos, Ferramentas..." required autofocus>
                                @error('name') <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="description" class="mb-1.5 block text-xs font-bold uppercase tracking-[0.14em]" style="color:#4a5c4c;">
                                    Descri&ccedil;&atilde;o
                                </label>
                                <textarea id="description" name="description" rows="5"
                                    class="block w-full rounded-xl border-gray-300 bg-white text-sm text-slate-900 shadow-sm transition focus:border-green-600 focus:ring-green-600 @error('description') border-red-500 @enderror"
                                    placeholder="Descreva o tipo de produtos desta categoria...">{{ old('description') }}</textarea>
                                @error('description') <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </section>

                    <div class="mt-8 flex flex-col-reverse gap-3 border-t pt-5 sm:flex-row sm:items-center sm:justify-end"
                        style="border-color:#d4e8d6;">
                        <a href="{{ route('categories.index') }}"
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
                            Cadastrar categoria
                        </button>
                    </div>
                </form>
            </main>

            <aside class="space-y-5">
                <div class="overflow-hidden rounded-2xl border text-white shadow-sm"
                    style="border-color:#1a3d1f;background:radial-gradient(circle at 15% 10%,rgba(168,213,171,0.28),transparent 32%),linear-gradient(160deg,#214f27 0%,#143418 72%);box-shadow:0 18px 45px rgba(26,61,31,0.16);">
                    <div class="p-5">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="text-[11px] font-bold uppercase tracking-[0.22em]" style="color:rgba(255,255,255,0.55);">AgroStock</p>
                                <h2 class="mt-3 text-2xl font-bold leading-tight">Categorias deixam o estoque mais f&aacute;cil de encontrar.</h2>
                            </div>
                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl"
                                style="background:rgba(255,255,255,0.12);color:#a8d5ab;">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="h-5 w-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M4 12h10M4 17h7" />
                                </svg>
                            </div>
                        </div>

                        <div class="mt-8 grid grid-cols-2 gap-3">
                            <div class="rounded-xl border p-3" style="border-color:rgba(255,255,255,0.12);background:rgba(255,255,255,0.06);">
                                <p class="text-[10px] font-bold uppercase tracking-[0.16em]" style="color:rgba(255,255,255,0.48);">Cadastro</p>
                                <p class="mt-2 text-lg font-bold">Categoria</p>
                            </div>
                            <div class="rounded-xl border p-3" style="border-color:rgba(255,255,255,0.12);background:rgba(255,255,255,0.06);">
                                <p class="text-[10px] font-bold uppercase tracking-[0.16em]" style="color:rgba(255,255,255,0.48);">Filtro</p>
                                <p class="mt-2 text-lg font-bold">Cat&aacute;logo</p>
                            </div>
                        </div>
                    </div>
                    <div class="border-t px-5 py-4 text-sm" style="border-color:rgba(255,255,255,0.12);color:rgba(255,255,255,0.7);">
                        Campos marcados com <span class="font-bold text-white">*</span> s&atilde;o obrigat&oacute;rios.
                    </div>
                </div>

                <div class="rounded-2xl border bg-white p-5 shadow-sm" style="border-color:#d4e8d6;">
                    <h3 class="text-sm font-bold uppercase tracking-[0.16em]" style="color:#1a3d1f;">Boas pr&aacute;ticas</h3>
                    <div class="mt-4 space-y-3">
                        <div class="flex items-center gap-3 rounded-xl border px-3 py-3" style="border-color:#edf4ee;background:#fbfdfb;">
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg" style="background:#eaf6e9;color:#2d6a35;">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="h-4 w-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7 10 17l-5-5" />
                                </svg>
                            </span>
                            <div>
                                <p class="text-sm font-semibold text-slate-900">Nome curto</p>
                                <p class="text-xs text-slate-500">Facilita filtros e relat&oacute;rios</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 rounded-xl border px-3 py-3" style="border-color:#edf4ee;background:#fbfdfb;">
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg" style="background:#fff7ed;color:#c2410c;">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="h-4 w-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h8M8 12h8M8 17h5" />
                                </svg>
                            </span>
                            <div>
                                <p class="text-sm font-semibold text-slate-900">Descri&ccedil;&atilde;o objetiva</p>
                                <p class="text-xs text-slate-500">Explique o que entra na categoria</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 rounded-xl border px-3 py-3" style="border-color:#edf4ee;background:#fbfdfb;">
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg" style="background:#eff6ff;color:#1d4ed8;">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="h-4 w-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M4 12h16M4 17h16" />
                                </svg>
                            </span>
                            <div>
                                <p class="text-sm font-semibold text-slate-900">Padr&atilde;o consistente</p>
                                <p class="text-xs text-slate-500">Evite categorias duplicadas</p>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</div>
@endsection
