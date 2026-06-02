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

        $this->redirect('/', navigate: true);
    }
}; ?>

{{--
    UM ÚNICO elemento raiz para o Livewire.
    O x-data do Alpine controla o menu mobile.
    Em lg+ → sidebar vertical fixa.
    Em < lg → topbar com menu colapsável.
--}}
<div x-data="{ mobileOpen: false, userOpen: false }">
    
    <aside class="hidden lg:flex flex-col w-60 shrink-0 h-screen sticky top-0 border-r" style="background:#1a3d1f;border-color:rgba(168,213,171,0.1); ">
        <div class="flex items-center gap-3 px-6 py-5 border-b" style="border-color:rgba(168,213,171,0.1);">
            <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center text-lg shrink-0"
                    style="background:#4caf50;">
                    🌾
                </div>
            </a>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">

            <p class="text-[10px] font-semibold uppercase tracking-widest px-3 mb-2 mt-1"
            style="color:rgba(168,213,171,0.5);">Principal</p>

            <a href="{{ route('dashboard') }}" wire:navigate
            class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-all hover:bg-white/5"
            @if(request()->routeIs('dashboard'))
                style="color:#f9f6f0;background:rgba(76,175,80,0.14);"
            @else
                style="color:rgba(255,255,255,0.6);"
            @endif>
                <span @if(request()->routeIs('dashboard')) style="color:#4caf50;" @endif><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-house-icon lucide-house"><path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8"/><path d="M3 10a2 2 0 0 1 .709-1.528l7-6a2 2 0 0 1 2.582 0l7 6A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg></span>
                Dashboard
            </a>

            <a href="{{ route('products.index') }}"
            class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-all hover:bg-white/5"
            @if(request()->routeIs('products.*'))
                style="color:#f9f6f0;background:rgba(76,175,80,0.14);"
            @else
                style="color:rgba(255,255,255,0.6);"
            @endif>
                <span @if(request()->routeIs('products.*')) style="color:#4caf50;" @endif><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-package-open-icon lucide-package-open"><path d="M12 22v-9"/><path d="M15.17 2.21a1.67 1.67 0 0 1 1.63 0L21 4.57a1.93 1.93 0 0 1 0 3.36L8.82 14.79a1.655 1.655 0 0 1-1.64 0L3 12.43a1.93 1.93 0 0 1 0-3.36z"/><path d="M20 13v3.87a2.06 2.06 0 0 1-1.11 1.83l-6 3.08a1.93 1.93 0 0 1-1.78 0l-6-3.08A2.06 2.06 0 0 1 4 16.87V13"/><path d="M21 12.43a1.93 1.93 0 0 0 0-3.36L8.83 2.2a1.64 1.64 0 0 0-1.63 0L3 4.57a1.93 1.93 0 0 0 0 3.36l12.18 6.86a1.636 1.636 0 0 0 1.63 0z"/></svg></span>
                Estoque
            </a>

            <a href="{{ route('categories.index') }}" wire:navigate
            class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-all hover:bg-white/5"
            @if(request()->routeIs('categories.*'))
                style="color:#f9f6f0;background:rgba(76,175,80,0.14);"
            @else
                style="color:rgba(255,255,255,0.6);"
            @endif>
                <span @if(request()->routeIs('categories.*')) style="color:#4caf50;" @endif><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-tags-icon lucide-tags"><path d="M13.172 2a2 2 0 0 1 1.414.586l6.71 6.71a2.4 2.4 0 0 1 0 3.408l-4.592 4.592a2.4 2.4 0 0 1-3.408 0l-6.71-6.71A2 2 0 0 1 6 9.172V3a1 1 0 0 1 1-1z"/><path d="M2 7v6.172a2 2 0 0 0 .586 1.414l6.71 6.71a2.4 2.4 0 0 0 3.191.193"/><circle cx="10.5" cy="6.5" r=".5" fill="currentColor"/></svg></span>
                Categorias
            </a>

            <a href="{{ route('stock-movements.index') }}" wire:navigate
            class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-all hover:bg-white/5"
            @if(request()->routeIs('stock-movements.*'))
                style="color:#f9f6f0;background:rgba(76,175,80,0.14);"
            @else
                style="color:rgba(255,255,255,0.6);"
            @endif>
                <span @if(request()->routeIs('stock-movements.*')) style="color:#4caf50;" @endif class="w-5"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M4.00488 5.00275V19.0027H20.0049V5.00275H4.00488ZM3.00488 3.00275H21.0049C21.5572 3.00275 22.0049 3.45046 22.0049 4.00275V20.0027C22.0049 20.555 21.5572 21.0027 21.0049 21.0027H3.00488C2.4526 21.0027 2.00488 20.555 2.00488 20.0027V4.00275C2.00488 3.45046 2.4526 3.00275 3.00488 3.00275ZM15.0049 7.00275L18.5049 10.0027L15.0049 13.0027V11.0027H11.0049V9.00275H15.0049V7.00275ZM9.00488 17.0027L5.50488 14.0027L9.00488 11.0027V13.0027H13.0049V15.0027H9.00488V17.0027Z"></path></svg></span>
                Movimentações
            </a>

            <a href="{{ route('suppliers.index') }}"
            class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-all hover:bg-white/5"
            @if(request()->routeIs('suppliers.*'))
                style="color:#f9f6f0;background:rgba(76,175,80,0.14);"
            @else
                style="color:rgba(255,255,255,0.6);"
            @endif>
                <span @if(request()->routeIs('suppliers.*')) style="color:#4caf50;" @endif class="w-5"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-truck-icon lucide-truck"><path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"/><path d="M15 18H9"/><path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"/><circle cx="17" cy="18" r="2"/><circle cx="7" cy="18" r="2"/></svg></span> 
                Fornecedores
            </a>

            <br>
            <p class="text-[10px] font-semibold uppercase tracking-widest px-3 mb-2 mt-4"
            style="color:rgba(168,213,171,0.5);">Relatórios</p>

            <a href="{{ route('analytics.index') }}"
            class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-all hover:bg-white/5"
            @if(request()->routeIs('analytics.*'))
                style="color:#f9f6f0;background:rgba(76,175,80,0.14);"
            @else
                style="color:rgba(255,255,255,0.6);"
            @endif>
                <span @if(request()->routeIs('analytics.*')) style="color:#4caf50;" @endif class="w-5"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chart-line-icon lucide-chart-line"><path d="M3 3v16a2 2 0 0 0 2 2h16"/><path d="m19 9-5 5-4-4-3 3"/></svg></span>
                 Análises
            </a>

            <a href="{{ route('expiration-date.index')}}"
            class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-all hover:bg-white/5"
             @if(request()->routeIs('expiration-date.*'))
                style="color:#f9f6f0;background:rgba(76,175,80,0.14);"
            @else
                style="color:rgba(255,255,255,0.6);"
            @endif>
                <span  @if(request()->routeIs('expiration-date.*')) style="color:#4caf50;" @endif class="w-5"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-icon lucide-calendar"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg></span> 
                Validades
            </a>

            <a href="{{ route('export.index')}}"
            class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-all hover:bg-white/5"
            @if(request()->routeIs('export.*'))
                style="color:#f9f6f0;background:rgba(76,175,80,0.14);"
            @else
                style="color:rgba(255,255,255,0.6);"
            @endif>
                <span @if(request()->routeIs('export.*')) style="color:#4caf50;" @endif class="w-5"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-down-to-line-icon lucide-arrow-down-to-line"><path d="M12 17V3"/><path d="m6 11 6 6 6-6"/><path d="M19 21H5"/></svg></span> 
                Exportar
            </a>

            <a href="{{ route('audit-logs.index')}}"
            class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-all hover:bg-white/5"
            @if(request()->routeIs('audit-logs.*'))
                style="color:#f9f6f0;background:rgba(76,175,80,0.14);"
            @else
                style="color:rgba(255,255,255,0.6);"
            @endif>
                <span @if(request()->routeIs('audit-logs.*')) style="color:#4caf50;" @endif class="w-5"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v18"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7H14a3.5 3.5 0 0 1 0 7H6"/></svg></span>
                Auditoria
            </a>

        </nav>

        {{-- User footer com dropdown --}}
        <div class="px-3 pb-4 pt-3 border-t" style="border-color:rgba(168,213,171,0.1);">
            <div x-data="{ dropOpen: false }" class="relative">

                <button @click="dropOpen = !dropOpen"
                        class="w-full flex items-center gap-3 rounded-xl px-3 py-2.5 hover:bg-white/5 transition-all text-left">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold text-white shrink-0"
                        style="background:linear-gradient(135deg,#4caf50,#2d6a35);">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                    <div class="flex-1 min-w-0"
                        x-data="{{ json_encode(['name' => auth()->user()->name]) }}"
                        x-on:profile-updated.window="name = $event.detail.name">
                        <p class="text-sm font-semibold text-white truncate" x-text="name">
                            {{ auth()->user()->name }}
                        </p>
                        <p class="text-[11px] truncate" style="color:rgba(168,213,171,0.6);">
                            {{ auth()->user()->email }}
                        </p>
                    </div>
                    <svg class="w-3.5 h-3.5 shrink-0 transition-transform duration-200"
                        :class="dropOpen ? 'rotate-180' : ''"
                        style="color:rgba(255,255,255,0.3);"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                {{-- Dropdown --}}
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

                    <a href="{{ route('profile') }}" wire:navigate
                    class="flex items-center gap-2.5 px-4 py-3 text-sm transition-colors hover:bg-white/5"
                    style="color:rgba(255,255,255,0.75);">
                        <span>👤</span> Perfil
                    </a>

                    <div style="height:1px;background:rgba(168,213,171,0.1);"></div>

                    <button wire:click="logout"
                            class="w-full flex items-center gap-2.5 px-4 py-3 text-sm transition-colors hover:bg-red-900/30 text-left"
                            style="color:rgba(255,100,100,0.85);">
                        <span>🚪</span> Sair
                    </button>
                </div>
            </div>
        </div>
    </aside>
</div>   
