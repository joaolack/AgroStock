<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component
{
    public string $name = '';
    public string $email = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function sendVerification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<section>
    <div class="flex flex-col gap-4 border-b pb-5 sm:flex-row sm:items-start sm:justify-between" style="border-color:#d4e8d6;">
        <div class="flex items-start gap-3">
            <div class="h-11 w-11 shrink-0 rounded-xl flex items-center justify-center" style="background:#eef7ef;color:#2d6a35;">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6.75a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.25a7.5 7.5 0 0 1 15 0" />
                </svg>
            </div>
            <div>
                <h2 class="font-display text-lg font-bold" style="color:#1a3d1f;">
                    {{ __('Informações do perfil') }}
                </h2>
                <p class="mt-1 text-sm leading-6" style="color:#8a9e8c;">
                    {{ __('Atualize o nome exibido no sistema e o e-mail usado para acesso.') }}
                </p>
            </div>
        </div>

        <span class="inline-flex w-fit items-center rounded-full px-3 py-1 text-xs font-semibold" style="background:#eef7ef;color:#2d6a35;">
            Identidade
        </span>
    </div>

    <form wire:submit="updateProfileInformation" class="mt-6 space-y-5">
        <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">
            <div>
                <label for="name" class="block text-xs font-semibold uppercase tracking-wide" style="color:#6e876f;">{{ __('Nome') }}</label>
                <input
                    wire:model="name"
                    id="name"
                    name="name"
                    type="text"
                    class="mt-2 block w-full rounded-xl border px-4 py-3 text-sm shadow-sm transition focus:border-green-600 focus:ring-green-600"
                    style="border-color:#d4e8d6;color:#1a3d1f;"
                    required
                    autofocus
                    autocomplete="name"
                />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div>
                <label for="email" class="block text-xs font-semibold uppercase tracking-wide" style="color:#6e876f;">{{ __('E-mail') }}</label>
                <input
                    wire:model="email"
                    id="email"
                    name="email"
                    type="email"
                    class="mt-2 block w-full rounded-xl border px-4 py-3 text-sm shadow-sm transition focus:border-green-600 focus:ring-green-600"
                    style="border-color:#d4e8d6;color:#1a3d1f;"
                    required
                    autocomplete="username"
                />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />
            </div>
        </div>

        @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
            <div class="rounded-xl border px-4 py-3" style="border-color:#fcd34d;background:#fffbeb;">
                <p class="text-sm leading-6" style="color:#92400e;">
                    {{ __('Seu e-mail ainda não foi verificado.') }}
                    <button wire:click.prevent="sendVerification" class="font-semibold underline decoration-2 underline-offset-4 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
                        {{ __('Reenviar verificação') }}
                    </button>
                </p>

                @if (session('status') === 'verification-link-sent')
                    <p class="mt-2 text-sm font-semibold" style="color:#166534;">
                        {{ __('Enviamos um novo link de verificação para o seu e-mail.') }}
                    </p>
                @endif
            </div>
        @endif

        <div class="flex flex-col gap-3 pt-1 sm:flex-row sm:items-center">
            <x-primary-button class="inline-flex w-full items-center justify-center gap-2 px-5 sm:w-auto">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75 10 18.25 19.5 5.75" />
                </svg>
                {{ __('Salvar alterações') }}
            </x-primary-button>

            <x-action-message class="!text-green-700 text-sm font-semibold" on="profile-updated">
                {{ __('Perfil atualizado.') }}
            </x-action-message>
        </div>
    </form>
</section>
