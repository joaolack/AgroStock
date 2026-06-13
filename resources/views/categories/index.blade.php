@extends('layouts.app')

@section('slot')
<div class="flex min-h-screen flex-1 flex-col overflow-hidden">
    <header class="sticky top-0 z-20 flex items-center justify-between border-b bg-white/80 px-6 py-3.5 backdrop-blur-md"
        style="border-color:#d4e8d6;">
        <div class="flex items-center gap-3">
            <button class="lg:hidden rounded-lg p-2 transition colors hover:bg-agro-pale" style="color:#4a5c4c;">
                Menu
            </button>
            <div>
                <h1 class="font-display text-xl font-bold tracking-tight" style="color:#1a3d1f;">Categorias</h1>
                <p class="text-[11px]" style="color:#8a9e8c;">Organize o catalogo por tipos de produtos</p>
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
                style="border-color:#d4e8d6;">
                <div>
                    <h2 class="font-display text-base font-bold" style="color:#1a3d1f;">Lista de Categorias</h2>
                    <p class="text-xs" style="color:#8a9e8c;">
                        {{ $categories->total() }} {{ $categories->total() === 1 ? 'categoria encontrada' : 'categorias encontradas' }}
                        @if (request('search'))
                            para "{{ request('search') }}"
                        @else
                            de {{ $totalCategories }} cadastradas
                        @endif
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <form action="{{ route('categories.index') }}" method="GET" class="relative">
                        <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-sm" style="color:#8a9e8c;"><x-fas-magnifying-glass class="h-3 w-auto"/></span>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar categoria..."
                            class="w-52 rounded-xl border py-2 pl-8 pr-3 text-sm transition-all duration-200 focus:ring-4"
                            style="border-color:#d4e8d6;background:#f9f6f0;color:#1a3d1f;"
                            onfocus="this.style.borderColor='#4caf50';this.style.background='#fff';this.style.boxShadow='0 0 0 4px rgba(76,175,80,0.1)'"
                            onblur="this.style.borderColor='#d4e8d6';this.style.background='#f9f6f0';this.style.boxShadow='none'">
                    </form>

                    @if (request('search'))
                        <a href="{{ route('categories.index') }}" class="rounded-xl border px-4 py-2 text-sm font-semibold transition-all duration-200 hover:bg-agro-pale"
                            style="border-color:#d4e8d6;color:#4a5c4c;">
                            Limpar
                        </a>
                    @endif

                    <a href="{{ route('categories.create') }}" class="flex items-center gap-1.5 rounded-xl px-4 py-2 text-sm font-semibold text-white transition-all duration-200 hover:-translate-y-px hover:shadow-lg"
                        style="background:#1a3d1f;"
                        onmouseover="this.style.background='#2d6a35'"
                        onmouseout="this.style.background='#1a3d1f'">
                        <span class="text-base leading-none">+</span>
                        Adicionar Categoria
                    </a>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b" style="border-color:#d4e8d6;background:#f9f6f0;">
                            <th class="px-3 py-3 text-left text-[11px] font-semibold uppercase tracking-wider" style="color:#8a9e8c;">Nome</th>
                            <th class="px-3 py-3 text-left text-[11px] font-semibold uppercase tracking-wider" style="color:#8a9e8c;">Descri&ccedil;&atilde;o</th>
                            <th class="px-3 py-3 text-left text-[11px] font-semibold uppercase tracking-wider" style="color:#8a9e8c;">Produtos</th>
                            <th class="px-3 py-3 text-left text-[11px] font-semibold uppercase tracking-wider" style="color:#8a9e8c;">A&ccedil;&otilde;es</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y" style="divide-color:#eef7ef;">
                        @forelse ($categories as $category)
                            <tr class="prod-row animate-fadeIn">
                                <td class="whitespace-nowrap px-3 py-3.5 text-gray-900">
                                    <p class="font-semibold">{{ $category->name }}</p>
                                </td>
                                <td class="max-w-xl px-3 py-3.5 text-gray-900">
                                    <p class="line-clamp-2 text-sm">{{ $category->description ?? '-' }}</p>
                                </td>
                                <td class="whitespace-nowrap px-3 py-3.5 text-gray-900">
                                    <p class="font-semibold">{{ $category->products_count }} produto(s)</p>
                                </td>
                                <td class="whitespace-nowrap px-3 py-3.5 text-gray-900">
                                    <a href="{{ route('categories.edit', $category->id) }}"
                                        class="mr-3 text-indigo-600 transition duration-150 hover:text-indigo-900">
                                        Editar
                                    </a>

                                    @if ($category->products_count > 0)
                                        <div x-data="{ open: false }" class="inline-block">
                                            <button type="button" @click="open = true"
                                                class="text-orange-600 transition duration-150 hover:text-orange-900">
                                                Excluir
                                            </button>

                                            <div x-show="open" x-cloak @keydown.escape.window="open = false"
                                                class="fixed inset-0 z-[9999] overflow-y-auto" style="display: none;">
                                                <div class="fixed inset-0 bg-black/50"></div>

                                                <div class="flex min-h-screen items-center justify-center p-4">
                                                    <div @click.away="open = false" @click.stop
                                                        x-transition:enter="transition ease-out duration-300"
                                                        x-transition:enter-start="opacity-0 scale-95"
                                                        x-transition:enter-end="opacity-100 scale-100"
                                                        x-transition:leave="transition ease-in duration-200"
                                                        x-transition:leave-start="opacity-100 scale-100"
                                                        x-transition:leave-end="opacity-0 scale-95"
                                                        class="relative z-[9999] w-full max-w-xl rounded-2xl border bg-white p-6 shadow-xl"
                                                        style="border-color:#d4e8d6;">
                                                        <div class="mb-4 flex items-center gap-4">
                                                            <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full"
                                                                style="background:#fef9c3;color:#854d0e;">
                                                                !
                                                            </div>
                                                            <div>
                                                                <h3 class="font-display text-lg font-bold" style="color:#1a3d1f;">
                                                                    N&atilde;o &eacute; possivel excluir
                                                                </h3>
                                                                <p class="text-sm" style="color:#8a9e8c;">
                                                                    Existem produtos vinculados a esta categoria.
                                                                </p>
                                                            </div>
                                                        </div>

                                                        <div class="space-y-3">
                                                            <p class="break-words text-sm" style="color:#4a5c4c;">
                                                                A categoria <strong>"{{ $category->name }}"</strong> possui
                                                                <strong style="color:#854d0e;">{{ $category->products_count }} produto(s)</strong>.
                                                            </p>
                                                            <div class="rounded-xl border-l-4 p-3" style="border-color:#4caf50;background:#eef7ef;">
                                                                <p class="break-words text-sm" style="color:#1a3d1f;">
                                                                    Remova ou altere a categoria dos produtos vinculados primeiro.
                                                                </p>
                                                            </div>
                                                        </div>

                                                        <div class="mt-5 flex justify-end">
                                                            <button type="button" @click.prevent="open = false"
                                                                class="rounded-xl px-4 py-2 text-sm font-semibold text-white transition-all duration-200 hover:-translate-y-px"
                                                                style="background:#1a3d1f;"
                                                                onmouseover="this.style.background='#2d6a35'"
                                                                onmouseout="this.style.background='#1a3d1f'">
                                                                Entendi
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="inline-block"
                                            onsubmit="return confirm('Tem certeza que deseja excluir esta categoria?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-600 transition duration-150 hover:text-red-900">
                                                Excluir
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-3 py-6 text-center text-sm text-gray-500">
                                    Nenhuma categoria encontrada.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div>
                {{ $categories->links('vendor.pagination.agro') }}
            </div>
        </div>
    </div>
</div>
@endsection
