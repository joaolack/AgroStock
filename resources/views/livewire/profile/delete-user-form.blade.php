<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component
{
    public string $password = '';

    /**
     * Delete the currently authenticated user.
     */
    public function deleteUser(Logout $logout): void
    {
        $this->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);

        tap(Auth::user(), $logout(...))->delete();

        $this->redirect('/');
    }
}; ?>

<section>
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-start gap-3">
            <div class="h-11 w-11 shrink-0 rounded-xl flex items-center justify-center" style="background:#fee2e2;color:#b91c1c;">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166M19.228 5.79 18.16 19.673A2.25 2.25 0 0 1 15.916 21.75H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .563c.34-.059.68-.114 1.022-.166m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                </svg>
            </div>
            <div>
                <h2 class="font-display text-lg font-bold" style="color:#991b1b;">
                    {{ __('Excluir conta') }}
                </h2>
                <p class="mt-1 max-w-2xl text-sm leading-6" style="color:#7f1d1d;">
                    {{ __('Esta ação remove permanentemente a conta e os dados associados. Use apenas se tiver certeza de que não precisará mais do acesso.') }}
                </p>
            </div>
        </div>

        <button
            type="button"
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
            class="inline-flex w-full items-center justify-center gap-2 rounded-xl px-5 py-3 text-sm font-semibold text-white transition-all duration-200 hover:-translate-y-px hover:shadow-lg sm:w-auto"
            style="background:#b91c1c;"
        >
            <x-fas-triangle-exclamation class="h-4 w-auto"/>
            {{ __('Excluir conta') }}
        </button>
    </div>

    <x-modal name="confirm-user-deletion" :show="$errors->isNotEmpty()" focusable>
        <form wire:submit="deleteUser" class="p-6">
            <div class="flex items-start gap-3">
                <div class="h-11 w-11 shrink-0 rounded-xl flex items-center justify-center" style="background:#fee2e2;color:#b91c1c;">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0 3.75h.008v.008H12V16.5Zm8.25 3.75H3.75L12 3.75l8.25 16.5Z" />
                    </svg>
                </div>
                <div>
                    <h2 class="font-display text-lg font-bold" style="color:#991b1b;">
                        {{ __('Confirmar exclusão da conta') }}
                    </h2>

                    <p class="mt-2 text-sm leading-6" style="color:#4a5c4c;">
                        {{ __('Digite sua senha para confirmar. Depois da exclusão, esta conta não poderá ser recuperada.') }}
                    </p>
                </div>
            </div>

            <div class="mt-6 rounded-xl border p-4" style="border-color:#fecaca;background:#fff7f7;">
                <label for="password" class="block text-xs font-semibold uppercase tracking-wide" style="color:#991b1b;">{{ __('Senha') }}</label>

                <input
                    wire:model="password"
                    id="password"
                    name="password"
                    type="password"
                    class="mt-2 block w-full rounded-xl border px-4 py-3 text-sm shadow-sm transition focus:border-red-600 focus:ring-red-600"
                    style="border-color:#fecaca;color:#1a3d1f;"
                    placeholder="{{ __('Digite sua senha') }}"
                />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                <button
                    type="button"
                    x-on:click="$dispatch('close')"
                    class="inline-flex items-center justify-center rounded-xl border px-5 py-3 text-sm font-semibold transition hover:bg-gray-50"
                    style="border-color:#d4e8d6;color:#4a5c4c;"
                >
                    {{ __('Cancelar') }}
                </button>

                <button
                    type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-xl px-5 py-3 text-sm font-semibold text-white transition-all duration-200 hover:-translate-y-px hover:shadow-lg"
                    style="background:#b91c1c;"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166M19.228 5.79 18.16 19.673A2.25 2.25 0 0 1 15.916 21.75H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .563c.34-.059.68-.114 1.022-.166m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                    </svg>
                    {{ __('Excluir definitivamente') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>
