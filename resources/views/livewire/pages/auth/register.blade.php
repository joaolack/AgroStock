<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false));
    }
}; ?>

<div class="relative min-h-screen overflow-hidden bg-[#f9f6f0] px-5 py-8 text-gray-950 sm:px-8">
    <main class="relative z-10 mx-auto flex min-h-[calc(100vh-4rem)] w-full max-w-3xl flex-col items-center justify-center">
        <section class="flex min-h-[760px] w-full flex-col justify-center rounded-lg border bg-white p-7 shadow-2xl shadow-[#1a3d1f]/10 sm:p-10"
                 style="border-color:#d4e8d6;">
            <div class="mb-9 flex items-center justify-center gap-3">
                <img
                    src="{{ asset('images/logo-agrostock.png') }}"
                    alt="AgroStock logo"
                    class="h-16 w-auto sm:h-20"
                >
                <img
                    src="{{ asset('images/agrostock-tipografia.png') }}"
                    alt="AgroStock"
                    class="h-9 w-auto sm:h-11"
                >
            </div>

            <div class="mb-8 text-center">
                <p class="mb-2 text-xs font-bold uppercase tracking-[0.26em]" style="color:#6f7f71;">
                    Nova conta
                </p>
                <h1 class="font-serif text-4xl font-bold leading-tight tracking-tight sm:text-5xl" style="color:#1a3d1f;">
                    Crie seu acesso ao AgroStock
                </h1>
                <p class="mx-auto mt-3 max-w-xl text-sm leading-6" style="color:#6f7f71;">
                    Cadastre-se para organizar produtos, lotes e movimentações de estoque em um só lugar.
                </p>
            </div>

            <form wire:submit="register" class="grid gap-5">
                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-900">
                        {{ __('Nome') }}
                    </label>
                    <div class="relative mt-2">
                        <span class="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2" style="color:#6f7f71;">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M20 21a8 8 0 0 0-16 0m12-13a4 4 0 1 1-8 0 4 4 0 0 1 8 0Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                        <input
                            wire:model="name"
                            id="name"
                            name="name"
                            type="text"
                            required
                            autofocus
                            autocomplete="name"
                            placeholder="Seu nome completo"
                            class="h-12 w-full rounded-lg border bg-[#f9f6f0] pl-11 pr-4 text-sm text-gray-950 transition duration-200 placeholder:text-gray-400 focus:border-[#2d6a35] focus:bg-white focus:ring-4 focus:ring-[#2d6a35]/10"
                            style="border-color:#d4e8d6;"
                        >
                    </div>
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-900">
                        {{ __('Email') }}
                    </label>
                    <div class="relative mt-2">
                        <span class="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2" style="color:#6f7f71;">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="m4 6 8 7 8-7M5 5h14a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                        <input
                            wire:model="email"
                            id="email"
                            name="email"
                            type="email"
                            required
                            autocomplete="username"
                            placeholder="seu@email.com"
                            class="h-12 w-full rounded-lg border bg-[#f9f6f0] pl-11 pr-4 text-sm text-gray-950 transition duration-200 placeholder:text-gray-400 focus:border-[#2d6a35] focus:bg-white focus:ring-4 focus:ring-[#2d6a35]/10"
                            style="border-color:#d4e8d6;"
                        >
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="grid gap-5 sm:grid-cols-2" x-data="{ showPassword: false, showConfirmation: false }">
                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-900">
                            {{ __('Senha') }}
                        </label>
                        <div class="relative mt-2">
                            <span class="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2" style="color:#6f7f71;">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="M7 11V8a5 5 0 0 1 10 0v3m-9 0h8a2 2 0 0 1 2 2v6a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2v-6a2 2 0 0 1 2-2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                            <input
                                wire:model="password"
                                id="password"
                                name="password"
                                type="password"
                                required
                                autocomplete="new-password"
                                placeholder="••••••••"
                                :type="showPassword ? 'text' : 'password'"
                                class="h-12 w-full rounded-lg border bg-[#f9f6f0] pl-11 pr-12 text-sm text-gray-950 transition duration-200 placeholder:text-gray-400 focus:border-[#2d6a35] focus:bg-white focus:ring-4 focus:ring-[#2d6a35]/10"
                                style="border-color:#d4e8d6;"
                            >
                            <button
                                type="button"
                                @click="showPassword = !showPassword"
                                class="absolute right-3 top-1/2 -translate-y-1/2 rounded-md p-1 transition hover:bg-[#d4e8d6]/40 focus:outline-none focus:ring-2 focus:ring-[#2d6a35]"
                                style="color:#6f7f71;"
                                aria-label="Alternar visibilidade da senha"
                            >
                                <svg x-show="!showPassword" class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="M2.5 12S6 5 12 5s9.5 7 9.5 7-3.5 7-9.5 7-9.5-7-9.5-7Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <svg x-show="showPassword" style="display: none;" class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="m3 3 18 18M10.6 10.6A3 3 0 0 0 13.4 13.4M7.2 7.2C4.2 9 2.5 12 2.5 12S6 19 12 19c1.6 0 3-.5 4.2-1.2M10 5.2A9 9 0 0 1 12 5c6 0 9.5 7 9.5 7a16.2 16.2 0 0 1-2.1 3.1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-900">
                            {{ __('Confirmar senha') }}
                        </label>
                        <div class="relative mt-2">
                            <span class="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2" style="color:#6f7f71;">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="m9 12 2 2 4-5m5 3a8 8 0 1 1-16 0 8 8 0 0 1 16 0Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                            <input
                                wire:model="password_confirmation"
                                id="password_confirmation"
                                name="password_confirmation"
                                type="password"
                                required
                                autocomplete="new-password"
                                placeholder="••••••••"
                                :type="showConfirmation ? 'text' : 'password'"
                                class="h-12 w-full rounded-lg border bg-[#f9f6f0] pl-11 pr-12 text-sm text-gray-950 transition duration-200 placeholder:text-gray-400 focus:border-[#2d6a35] focus:bg-white focus:ring-4 focus:ring-[#2d6a35]/10"
                                style="border-color:#d4e8d6;"
                            >
                            <button
                                type="button"
                                @click="showConfirmation = !showConfirmation"
                                class="absolute right-3 top-1/2 -translate-y-1/2 rounded-md p-1 transition hover:bg-[#d4e8d6]/40 focus:outline-none focus:ring-2 focus:ring-[#2d6a35]"
                                style="color:#6f7f71;"
                                aria-label="Alternar visibilidade da confirmação de senha"
                            >
                                <svg x-show="!showConfirmation" class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="M2.5 12S6 5 12 5s9.5 7 9.5 7-3.5 7-9.5 7-9.5-7-9.5-7Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <svg x-show="showConfirmation" style="display: none;" class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="m3 3 18 18M10.6 10.6A3 3 0 0 0 13.4 13.4M7.2 7.2C4.2 9 2.5 12 2.5 12S6 19 12 19c1.6 0 3-.5 4.2-1.2M10 5.2A9 9 0 0 1 12 5c6 0 9.5 7 9.5 7a16.2 16.2 0 0 1-2.1 3.1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>
                </div>

                <div class="flex flex-col-reverse gap-3 pt-2 sm:flex-row sm:items-center sm:justify-between">
                    <a
                        class="rounded-full px-3 py-2 text-sm font-semibold transition hover:opacity-70 focus:outline-none focus:ring-2 focus:ring-[#2d6a35] focus:ring-offset-2"
                        href="{{ route('login') }}"
                        style="color:#2d6a35;"
                    >
                        {{ __('Já registrado?') }}
                    </a>

                    <button
                        type="submit"
                        class="inline-flex h-12 items-center justify-center gap-2 rounded-lg bg-[#1a3d1f] px-6 text-sm font-bold text-white shadow-lg shadow-[#1a3d1f]/20 transition duration-200 hover:-translate-y-0.5 hover:bg-[#2d6a35] focus:outline-none focus:ring-2 focus:ring-[#2d6a35] focus:ring-offset-2"
                    >
                        <span>{{ __('Registrar') }}</span>
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M5 12h14m0 0-5-5m5 5-5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </div>
            </form>
        </section>
    </main>
</div>
