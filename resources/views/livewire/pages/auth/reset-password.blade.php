<?php

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    #[Locked]
    public string $token = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Mount the component.
     */
    public function mount(string $token): void
    {
        $this->token = $token;

        $this->email = request()->string('email');
    }

    /**
     * Reset the password for the given user.
     */
    public function resetPassword(): void
    {
        $this->validate([
            'token' => ['required'],
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::reset(
            $this->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) {
                $user->forceFill([
                    'password' => Hash::make($this->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        if ($status != Password::PASSWORD_RESET) {
            $this->addError('email', __($status));

            return;
        }

        Session::flash('status', __($status));

        $this->redirectRoute('login');
    }
}; ?>

<div class="relative min-h-screen overflow-hidden bg-[#f9f6f0] px-5 py-8 text-gray-950 sm:px-8">
    <div class="absolute inset-x-0 top-0 h-28 border-b bg-white" style="border-color:#d4e8d6;"></div>
    <div class="absolute inset-x-0 bottom-0 h-40 border-t bg-[#eef7ef]" style="border-color:#d4e8d6;"></div>
    <div class="absolute inset-y-0 left-0 w-1.5 bg-[#1a3d1f]"></div>

    <main class="relative z-10 mx-auto flex min-h-[calc(100vh-4rem)] w-full max-w-2xl flex-col items-center justify-center">
        <section class="flex min-h-[680px] w-full flex-col justify-center rounded-lg border bg-white p-7 shadow-2xl shadow-[#1a3d1f]/10 sm:p-10"
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
                    Segurança da conta
                </p>
                <h1 class="font-serif text-4xl font-bold leading-tight tracking-tight sm:text-5xl" style="color:#1a3d1f;">
                    Redefina sua senha
                </h1>
                <p class="mx-auto mt-3 max-w-xl text-sm leading-6" style="color:#6f7f71;">
                    Informe o e-mail da conta e escolha uma nova senha para retomar o acesso ao AgroStock.
                </p>
            </div>

            <form wire:submit="resetPassword" class="grid gap-5" x-data="{ showPassword: false, showConfirmation: false }">
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-900">
                        {{ __('E-mail') }}
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
                            autofocus
                            autocomplete="username"
                            placeholder="seu@email.com"
                            class="h-12 w-full rounded-lg border bg-[#f9f6f0] pl-11 pr-4 text-sm text-gray-950 transition duration-200 placeholder:text-gray-400 focus:border-[#2d6a35] focus:bg-white focus:ring-4 focus:ring-[#2d6a35]/10"
                            style="border-color:#d4e8d6;"
                        >
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-900">
                        {{ __('Nova senha') }}
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

                <div class="pt-2">
                    <button
                        type="submit"
                        class="inline-flex h-12 w-full items-center justify-center gap-2 rounded-lg bg-[#1a3d1f] px-6 text-sm font-bold text-white shadow-lg shadow-[#1a3d1f]/20 transition duration-200 hover:-translate-y-0.5 hover:bg-[#2d6a35] focus:outline-none focus:ring-2 focus:ring-[#2d6a35] focus:ring-offset-2"
                    >
                        <span>{{ __('Redefinir senha') }}</span>
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M5 12h14m0 0-5-5m5 5-5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </div>
            </form>
        </section>
    </main>
</div>
