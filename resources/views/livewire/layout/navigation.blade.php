<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/');
    }
}; ?>

@php
    $navSections = [
        [
            'label' => 'Principal',
            'items' => [
                [
                    'label' => 'Dashboard',
                    'route' => 'dashboard',
                    'active' => 'dashboard',
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8"/><path d="M3 10a2 2 0 0 1 .709-1.528l7-6a2 2 0 0 1 2.582 0l7 6A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>',
                ],
                [
                    'label' => 'Estoque',
                    'route' => 'products.index',
                    'active' => 'products.*',
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22v-9"/><path d="M15.17 2.21a1.67 1.67 0 0 1 1.63 0L21 4.57a1.93 1.93 0 0 1 0 3.36L8.82 14.79a1.655 1.655 0 0 1-1.64 0L3 12.43a1.93 1.93 0 0 1 0-3.36z"/><path d="M20 13v3.87a2.06 2.06 0 0 1-1.11 1.83l-6 3.08a1.93 1.93 0 0 1-1.78 0l-6-3.08A2.06 2.06 0 0 1 4 16.87V13"/><path d="M21 12.43a1.93 1.93 0 0 0 0-3.36L8.83 2.2a1.64 1.64 0 0 0-1.63 0L3 4.57a1.93 1.93 0 0 0 0 3.36l12.18 6.86a1.636 1.636 0 0 0 1.63 0z"/></svg>',
                ],
                [
                    'label' => 'Categorias',
                    'route' => 'categories.index',
                    'active' => 'categories.*',
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13.172 2a2 2 0 0 1 1.414.586l6.71 6.71a2.4 2.4 0 0 1 0 3.408l-4.592 4.592a2.4 2.4 0 0 1-3.408 0l-6.71-6.71A2 2 0 0 1 6 9.172V3a1 1 0 0 1 1-1z"/><path d="M2 7v6.172a2 2 0 0 0 .586 1.414l6.71 6.71a2.4 2.4 0 0 0 3.191.193"/><circle cx="10.5" cy="6.5" r=".5" fill="currentColor"/></svg>',
                ],
                [
                    'label' => 'Movimentações',
                    'route' => 'stock-movements.index',
                    'active' => 'stock-movements.*',
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M4.00488 5.00275V19.0027H20.0049V5.00275H4.00488ZM3.00488 3.00275H21.0049C21.5572 3.00275 22.0049 3.45046 22.0049 4.00275V20.0027C22.0049 20.555 21.5572 21.0027 21.0049 21.0027H3.00488C2.4526 21.0027 2.00488 20.555 2.00488 20.0027V4.00275C2.00488 3.45046 2.4526 3.00275 3.00488 3.00275ZM15.0049 7.00275L18.5049 10.0027L15.0049 13.0027V11.0027H11.0049V9.00275H15.0049V7.00275ZM9.00488 17.0027L5.50488 14.0027L9.00488 11.0027V13.0027H13.0049V15.0027H9.00488V17.0027Z"></path></svg>',
                ],
                [
                    'label' => 'Fornecedores',
                    'route' => 'suppliers.index',
                    'active' => 'suppliers.*',
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"/><path d="M15 18H9"/><path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"/><circle cx="17" cy="18" r="2"/><circle cx="7" cy="18" r="2"/></svg>',
                ],
            ],
        ],
        [
            'label' => 'Relatórios',
            'items' => [
                [
                    'label' => 'Análises',
                    'route' => 'analytics.index',
                    'active' => 'analytics.*',
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v16a2 2 0 0 0 2 2h16"/><path d="m19 9-5 5-4-4-3 3"/></svg>',
                ],
                [
                    'label' => 'Validades',
                    'route' => 'expiration-date.index',
                    'active' => 'expiration-date.*',
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg>',
                ],
                [
                    'label' => 'Exportar',
                    'route' => 'export.index',
                    'active' => 'export.*',
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 17V3"/><path d="m6 11 6 6 6-6"/><path d="M19 21H5"/></svg>',
                ],
                [
                    'label' => 'Auditoria',
                    'route' => 'audit-logs.index',
                    'active' => 'audit-logs.*',
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v18"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7H14a3.5 3.5 0 0 1 0 7H6"/></svg>',
                ],
            ],
        ],
    ];

    $desktopLinkClass = 'flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-all hover:bg-white/5';
    $mobileLinkClass = 'flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold transition-all hover:bg-white/10';
@endphp

<div
    x-data="{ mobileOpen: false }"
    x-on:open-mobile-navigation.window="mobileOpen = true"
    x-on:keydown.escape.window="mobileOpen = false"
    x-on:resize.window="if (window.innerWidth >= 1024) mobileOpen = false"
    x-effect="document.body.style.overflow = mobileOpen ? 'hidden' : ''"
>
    <aside class="hidden lg:flex flex-col w-64 shrink-0 h-screen sticky top-0 border-r"
        style="background:radial-gradient(circle at top, rgba(168,213,171,0.12) 0%, transparent 30%), linear-gradient(180deg, #214f27 0%, #1a3d1f 40%, #122d16 100%);border-color:rgba(168,213,171,0.1);">
        <div class="flex items-center gap-3 px-3 py-5 border-b" style="border-color:rgba(168,213,171,0.1);">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 rounded-2xl px-3 py-3 transition-all duration-200 hover:bg-white/5">
                <div class="flex h-16 w-16 items-center justify-center rounded-2xl">
                    <img src="{{ asset('images/logo-agrostock.png') }}" alt="AgroStock logo" class="h-12 w-auto rounded-xl">
                </div>
                <div class="leading-tight">
                    <h1 class="text-xl text-white font-bold tracking-tight">AgroStock</h1>
                    <p class="text-xs font-medium" style="color:rgba(255,255,255,0.6);">Gestão agropecuária</p>
                </div>
            </a>
        </div>

        <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">
            @foreach ($navSections as $section)
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.2em] px-3 mb-4 mt-4"
                        style="color:rgba(168,213,171,0.5);">{{ $section['label'] }}</p>
                </div>

                @foreach ($section['items'] as $item)
                    @php($active = request()->routeIs($item['active']))
                    <a href="{{ route($item['route']) }}"
                        class="{{ $desktopLinkClass }}"
                        style="{{ $active ? 'color:#f9f6f0;background:rgba(76,175,80,0.18);' : 'color:rgba(255,255,255,0.6);' }}">
                        <span class="flex h-6 w-6 shrink-0 items-center justify-center {{ $active ? '' : '' }}"
                            @if($active) style="color:#4caf50;" @endif>{!! $item['icon'] !!}</span>
                        {{ $item['label'] }}
                    </a>
                @endforeach
            @endforeach
        </nav>

        <div class="p-4 border-t" style="border-color:rgba(168,213,171,0.1);">
            <div x-data="{ dropOpen: false }" class="relative">
                <button type="button" @click="dropOpen = !dropOpen"
                    class="w-full flex items-center gap-3 rounded-xl px-3 py-2.5 hover:bg-white/5 transition-all text-left">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold text-white shrink-0"
                        style="background:linear-gradient(135deg,#4caf50,#2d6a35);">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                    <div class="flex-1 min-w-0"
                        x-data="{{ json_encode(['name' => auth()->user()->name]) }}"
                        x-on:profile-updated.window="name = $event.detail.name">
                        <p class="text-sm font-semibold text-white truncate" x-text="name">{{ auth()->user()->name }}</p>
                        <p class="text-[12px] truncate" style="color:rgba(255,255,255,0.6);">{{ auth()->user()->email }}</p>
                    </div>
                    <svg class="w-3.5 h-3.5 shrink-0 transition-transform duration-200"
                        :class="dropOpen ? 'rotate-180' : ''"
                        style="color:rgba(255,255,255,0.3);"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <div x-show="dropOpen"
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    @click.outside="dropOpen = false"
                    class="absolute bottom-full left-0 right-0 mb-2 rounded-xl overflow-hidden shadow-xl border"
                    style="background:#1e4a24;border-color:rgba(168,213,171,0.15);"
                    x-cloak>
                    <a href="{{ route('profile') }}"
                        class="flex items-center gap-2.5 px-4 py-3 text-sm transition-colors hover:bg-white/5"
                        style="color:rgba(255,255,255,0.75);">
                        <span><x-fas-user class="w-5 h-4" style="color:rgba(255,255,255,0.6);"/></span>
                        Perfil
                    </a>

                    <div style="height:1px;background:rgba(168,213,171,0.1);"></div>

                    <button type="button" wire:click="logout"
                        class="w-full flex items-center gap-2.5 px-4 py-3 text-sm transition-colors hover:bg-white/5 text-left"
                        style="color:rgba(255,255,255,0.75);">
                        <span><x-fas-sign-out-alt class="w-5 h-4" style="color:rgba(255,255,255,0.6);"/></span>
                        Sair
                    </button>
                </div>
            </div>
        </div>
    </aside>

    <div x-cloak x-show="mobileOpen" class="fixed inset-0 z-40 lg:hidden" aria-modal="true" role="dialog">
        <div x-show="mobileOpen"
            x-transition.opacity
            @click="mobileOpen = false"
            class="absolute inset-0 bg-slate-950/45 backdrop-blur-sm"></div>

        <aside x-show="mobileOpen"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="-translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="-translate-x-full"
            class="relative flex h-full w-[min(86vw,21rem)] flex-col border-r shadow-2xl"
            style="background:linear-gradient(180deg,#214f27 0%,#1a3d1f 48%,#122d16 100%);border-color:rgba(168,213,171,0.16);">
            <div class="flex items-center justify-between gap-3 border-b px-4 py-4" style="border-color:rgba(168,213,171,0.14);">
                <a href="{{ route('dashboard') }}" @click="mobileOpen = false" class="flex min-w-0 items-center gap-3 rounded-2xl px-1 py-1">
                    <img src="{{ asset('images/logo-agrostock.png') }}" alt="AgroStock logo" class="h-11 w-auto rounded-xl">
                    <div class="min-w-0 leading-tight">
                        <h1 class="truncate text-lg font-bold tracking-tight text-white">AgroStock</h1>
                        <p class="truncate text-xs font-medium" style="color:rgba(255,255,255,0.58);">Gestão agropecuária</p>
                    </div>
                </a>

                <button type="button" @click="mobileOpen = false"
                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl transition hover:bg-white/10"
                    style="color:rgba(255,255,255,0.72);"
                    aria-label="Fechar menu">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-4">
                @foreach ($navSections as $section)
                    <p class="px-3 pb-2 pt-4 text-[11px] font-bold uppercase tracking-[0.2em]"
                        style="color:rgba(168,213,171,0.52);">{{ $section['label'] }}</p>

                    @foreach ($section['items'] as $item)
                        @php($active = request()->routeIs($item['active']))
                        <a href="{{ route($item['route']) }}"
                            @click="mobileOpen = false"
                            class="{{ $mobileLinkClass }}"
                            style="{{ $active ? 'color:#f9f6f0;background:rgba(76,175,80,0.2);' : 'color:rgba(255,255,255,0.72);' }}">
                            <span class="flex h-6 w-6 shrink-0 items-center justify-center"
                                @if($active) style="color:#4caf50;" @endif>{!! $item['icon'] !!}</span>
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                @endforeach
            </nav>

            <div class="border-t p-4" style="border-color:rgba(168,213,171,0.14);">
                <div class="mb-3 flex items-center gap-3 rounded-xl px-2 py-2">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full text-sm font-bold text-white"
                        style="background:linear-gradient(135deg,#4caf50,#2d6a35);">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                    <div class="min-w-0"
                        x-data="{{ json_encode(['name' => auth()->user()->name]) }}"
                        x-on:profile-updated.window="name = $event.detail.name">
                        <p class="truncate text-sm font-semibold text-white" x-text="name">{{ auth()->user()->name }}</p>
                        <p class="truncate text-xs" style="color:rgba(255,255,255,0.56);">{{ auth()->user()->email }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <a href="{{ route('profile') }}" @click="mobileOpen = false"
                        class="flex items-center justify-center gap-2 rounded-xl border px-3 py-2.5 text-sm font-semibold transition hover:bg-white/5"
                        style="border-color:rgba(168,213,171,0.18);color:rgba(255,255,255,0.8);">
                        Perfil
                    </a>
                    <button type="button" wire:click="logout" @click="mobileOpen = false"
                        class="flex items-center justify-center gap-2 rounded-xl border px-3 py-2.5 text-sm font-semibold transition hover:bg-white/5"
                        style="border-color:rgba(168,213,171,0.18);color:rgba(255,255,255,0.8);">
                        Sair
                    </button>
                </div>
            </div>
        </aside>
    </div>
</div>
