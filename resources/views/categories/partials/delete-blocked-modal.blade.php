<div
    x-show="open"
    x-cloak
    @keydown.escape.window="open = false"
    class="fixed inset-0 z-[9999]"
    style="display:none;white-space:normal;"
    role="dialog"
    aria-modal="true"
    aria-labelledby="category-delete-blocked-title-{{ $category->id }}"
>
    <div
        class="absolute inset-0 bg-slate-950/55 backdrop-blur-sm"
        @click="open = false"
    ></div>

    <div class="relative flex min-h-screen items-center justify-center p-4">
        <div
            @click.stop
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-2 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 translate-y-2 scale-95"
            class="relative rounded-2xl border bg-white"
            style="box-sizing:border-box;width:680px;max-width:calc(100vw - 2rem);white-space:normal;border-color:#f5c98f;box-shadow:0 24px 70px rgba(26,61,31,0.24);"
        >
            <div class="p-5 sm:p-6">
                <div class="flex items-start gap-4">
                    <div
                        class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl"
                        style="background:#fff1df;color:#c2410c;"
                        aria-hidden="true"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01"/>
                        </svg>
                    </div>

                    <div class="min-w-0 flex-1">
                        <p class="text-[11px] font-bold uppercase tracking-[0.18em]" style="color:#c2410c;">
                            Exclusão bloqueada
                        </p>
                        <h3
                            id="category-delete-blocked-title-{{ $category->id }}"
                            class="mt-1 break-words font-display text-xl font-bold leading-tight"
                            style="color:#1a3d1f;"
                        >
                            Não é possível excluir esta categoria
                        </h3>
                        <p class="mt-2 break-words text-sm leading-6" style="color:#5f715f;">
                            Existem produtos vinculados a ela. Para manter a consistência do estoque, remova ou altere esses produtos antes de excluir a categoria.
                        </p>
                    </div>

                    <button
                        type="button"
                        @click="open = false"
                        class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl border bg-white text-slate-500 transition hover:bg-orange-50 hover:text-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2"
                        style="border-color:#fed7aa;"
                        aria-label="Fechar aviso"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 6 6 18M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div
                    class="mt-5 grid gap-3 rounded-2xl border p-4 sm:grid-cols-[minmax(0,1fr)_auto]"
                    style="border-color:#d4e8d6;background:#fbfdfb;"
                >
                    <div class="min-w-0">
                        <p class="text-[11px] font-bold uppercase tracking-[0.14em]" style="color:#8a9e8c;">
                            Categoria
                        </p>
                        <p class="mt-1 break-words text-sm font-bold" style="color:#1a3d1f;overflow-wrap:anywhere;">
                            {{ $category->name }}
                        </p>
                    </div>

                    <div class="shrink-0 sm:text-right">
                        <p class="text-[11px] font-bold uppercase tracking-[0.14em]" style="color:#8a9e8c;">
                            Vínculos
                        </p>
                        <p class="mt-1 text-sm font-bold" style="color:#c2410c;">
                            {{ $category->products_count }}
                            {{ $category->products_count === 1 ? 'produto' : 'produtos' }}
                        </p>
                    </div>
                </div>

                <div
                    class="mt-4 rounded-2xl border p-4"
                    style="border-color:#cfe7d1;background:#eef7ef;"
                >
                    <p class="break-words text-sm leading-6" style="color:#1a3d1f;">
                        Próximo passo: abra a listagem de produtos e troque a categoria dos itens vinculados, ou remova os produtos que não serão mais usados.
                    </p>
                </div>

                <div class="mt-5 flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
                    <button
                        type="button"
                        @click="open = false"
                        class="inline-flex h-11 items-center justify-center rounded-xl border px-4 text-sm font-bold transition hover:bg-orange-50 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2"
                        style="border-color:#fed7aa;color:#c2410c;"
                    >
                        Entendi
                    </button>

                    <a
                        href="{{ route('products.index', ['search' => $category->name]) }}"
                        class="inline-flex h-11 items-center justify-center rounded-xl px-4 text-sm font-bold text-white transition hover:-translate-y-px focus:outline-none focus:ring-2 focus:ring-green-700 focus:ring-offset-2"
                        style="background:#1a3d1f;"
                        onmouseover="this.style.background='#2d6a35'"
                        onmouseout="this.style.background='#1a3d1f'"
                    >
                        Ver produtos
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
