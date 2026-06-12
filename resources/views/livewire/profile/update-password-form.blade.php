<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;

new class extends Component
{
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Update the password for the currently authenticated user.
     */
    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');

            throw $e;
        }

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        $this->dispatch('password-updated');
    }
}; ?>

<section>
    <div class="flex flex-col gap-4 border-b pb-5 sm:flex-row sm:items-start sm:justify-between" style="border-color:#d4e8d6;">
        <div class="flex items-start gap-3">
            <div class="h-11 w-11 shrink-0 rounded-xl flex items-center justify-center" style="background:#eef7ef;color:#2d6a35;">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V7.875a4.5 4.5 0 0 0-9 0V10.5m-.75 0h10.5A1.5 1.5 0 0 1 18.75 12v6.75a1.5 1.5 0 0 1-1.5 1.5H6.75a1.5 1.5 0 0 1-1.5-1.5V12a1.5 1.5 0 0 1 1.5-1.5Z" />
                </svg>
            </div>
            <div>
                <h2 class="font-display text-lg font-bold" style="color:#1a3d1f;">
                    {{ __('Atualizar senha') }}
                </h2>
                <p class="mt-1 text-sm leading-6" style="color:#8a9e8c;">
                    {{ __('Use uma senha forte para proteger movimentações, relatórios e cadastros.') }}
                </p>
            </div>
        </div>

        <span class="inline-flex w-fit items-center rounded-full px-3 py-1 text-xs font-semibold" style="background:#fef3c7;color:#92400e;">
            Segurança
        </span>
    </div>

    <form wire:submit="updatePassword" class="mt-6 space-y-5">
        <div class="grid grid-cols-1 gap-5 lg:grid-cols-3">
            <div>
                <label for="update_password_current_password" class="block text-xs font-semibold uppercase tracking-wide" style="color:#6e876f;">{{ __('Senha atual') }}</label>
                <input
                    wire:model="current_password"
                    id="update_password_current_password"
                    name="current_password"
                    type="password"
                    class="mt-2 block w-full rounded-xl border px-4 py-3 text-sm shadow-sm transition focus:border-green-600 focus:ring-green-600"
                    style="border-color:#d4e8d6;color:#1a3d1f;"
                    autocomplete="current-password"
                />
                <x-input-error :messages="$errors->get('current_password')" class="mt-2" />
            </div>

            <div>
                <label for="update_password_password" class="block text-xs font-semibold uppercase tracking-wide" style="color:#6e876f;">{{ __('Nova senha') }}</label>
                <input
                    wire:model="password"
                    id="update_password_password"
                    name="password"
                    type="password"
                    class="mt-2 block w-full rounded-xl border px-4 py-3 text-sm shadow-sm transition focus:border-green-600 focus:ring-green-600"
                    style="border-color:#d4e8d6;color:#1a3d1f;"
                    autocomplete="new-password"
                />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div>
                <label for="update_password_password_confirmation" class="block text-xs font-semibold uppercase tracking-wide" style="color:#6e876f;">{{ __('Confirmar senha') }}</label>
                <input
                    wire:model="password_confirmation"
                    id="update_password_password_confirmation"
                    name="password_confirmation"
                    type="password"
                    class="mt-2 block w-full rounded-xl border px-4 py-3 text-sm shadow-sm transition focus:border-green-600 focus:ring-green-600"
                    style="border-color:#d4e8d6;color:#1a3d1f;"
                    autocomplete="new-password"
                />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>
        </div>

        <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
            <div class="rounded-xl border px-4 py-3" style="border-color:#d4e8d6;background:#fbfdfb;">
                <p class="text-xs font-semibold uppercase tracking-wide" style="color:#8a9e8c;">Recomendação</p>
                <p class="mt-1 text-sm" style="color:#4a5c4c;">Combine letras, números e símbolos.</p>
            </div>
            <div class="rounded-xl border px-4 py-3" style="border-color:#d4e8d6;background:#fbfdfb;">
                <p class="text-xs font-semibold uppercase tracking-wide" style="color:#8a9e8c;">Privacidade</p>
                <p class="mt-1 text-sm" style="color:#4a5c4c;">Evite repetir senhas usadas fora do sistema.</p>
            </div>
            <div class="rounded-xl border px-4 py-3" style="border-color:#d4e8d6;background:#fbfdfb;">
                <p class="text-xs font-semibold uppercase tracking-wide" style="color:#8a9e8c;">Confirmação</p>
                <p class="mt-1 text-sm" style="color:#4a5c4c;">Os campos são limpos após salvar.</p>
            </div>
        </div>

        <div class="flex flex-col gap-3 pt-1 sm:flex-row sm:items-center">
            <x-primary-button class="inline-flex w-full items-center justify-center gap-2 px-5 sm:w-auto">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75 10 18.25 19.5 5.75" />
                </svg>
                {{ __('Atualizar senha') }}
            </x-primary-button>

            <x-action-message class="!text-green-700 text-sm font-semibold" on="password-updated">
                {{ __('Senha atualizada.') }}
            </x-action-message>
        </div>
    </form>
</section>
