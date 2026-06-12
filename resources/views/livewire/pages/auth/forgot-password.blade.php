<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $this->only('email')
        );

        if ($status != Password::RESET_LINK_SENT) {
            $this->addError('email', __($status));

            return;
        }

        $this->reset('email');

        session()->flash('status', __($status));
    }
}; ?>

<div class="relative min-h-screen overflow-hidden bg-[#f9f6f0] px-5 py-8 text-gray-950 sm:px-8">
    <main class="relative z-10 mx-auto flex min-h-[calc(100vh-4rem)] w-full max-w-2xl flex-col items-center justify-center">
        <section class="flex min-h-[620px] w-full flex-col justify-center rounded-lg border bg-white p-7 shadow-2xl shadow-[#1a3d1f]/10 sm:p-10"
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
                    Recuperação de acesso
                </p>
                <h1 class="font-serif text-4xl font-bold leading-tight tracking-tight sm:text-5xl" style="color:#1a3d1f;">
                    Esqueceu sua senha?
                </h1>
                <p class="mx-auto mt-3 max-w-xl text-sm leading-6" style="color:#6f7f71;">
                    Informe o e-mail cadastrado e enviaremos um link seguro para criar uma nova senha.
                </p>
            </div>

            <x-auth-session-status class="mb-5 rounded-lg border px-4 py-3 text-sm"
                                   style="border-color:#d4e8d6;background:#eef7ef;color:#1a3d1f;"
                                   :status="session('status')" />

            <form wire:submit="sendPasswordResetLink" class="grid gap-5">
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
                            placeholder="seu@email.com"
                            class="h-12 w-full rounded-lg border bg-[#f9f6f0] pl-11 pr-4 text-sm text-gray-950 transition duration-200 placeholder:text-gray-400 focus:border-[#2d6a35] focus:bg-white focus:ring-4 focus:ring-[#2d6a35]/10"
                            style="border-color:#d4e8d6;"
                        >
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="flex flex-col-reverse gap-3 pt-2 sm:flex-row sm:items-center sm:justify-between">
                    <a
                        class="rounded-full px-3 py-2 text-sm font-semibold transition hover:opacity-70 focus:outline-none focus:ring-2 focus:ring-[#2d6a35] focus:ring-offset-2"
                        href="{{ route('login') }}"
                        style="color:#2d6a35;"
                    >
                        {{ __('Voltar ao login') }}
                    </a>

                    <button
                        type="submit"
                        class="inline-flex h-12 items-center justify-center gap-2 rounded-lg bg-[#1a3d1f] px-6 text-sm font-bold text-white shadow-lg shadow-[#1a3d1f]/20 transition duration-200 hover:-translate-y-0.5 hover:bg-[#2d6a35] focus:outline-none focus:ring-2 focus:ring-[#2d6a35] focus:ring-offset-2"
                    >
                        <span>{{ __('Enviar link de redefinição') }}</span>
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M5 12h14m0 0-5-5m5 5-5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </div>
            </form>
        </section>
    </main>
</div>
