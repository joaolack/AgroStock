<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false));
    }
}; ?>

<div class="min-h-screen grid grid-cols-1 lg:grid-cols-2 bg-[#eef7ef]">
    <div class="relative hidden lg:flex min-h-screen bg-white overflow-hidden items-center justify-center p-1">
        <img
            src="{{ asset('images/agrostock.png') }}"
            alt="AgroStock"
            class="h-auto w-auto max-h-[calc(100vh-3rem)] object-contain rounded-lg"
        >
    </div>
 


    <div class="relative flex items-center justify-center p-6 sm:p-10 lg:p-14 bg-white overflow-hidden">
        
        <div class="absolute top-0 right-0 w-72 h-72 pointer-events-none" 
             style="background:radial-gradient(ellipse at top right,#eef7ef 0%,transparent 70%);"></div>

        <div class="relative z-10 w-full max-w-xl">

            <!--Mobile-->
            <div class="lg:hidden text-center mb-20">
                <div class="inline-flex items-center gap-2 mb-2">
                    <img
                        src="{{ asset('images/logo-agrostock.png') }}"
                        alt="AgroStock logo"
                        class="h-24 w-auto mx-auto"
                    >

                    <img
                        src="{{ asset('images/agrostock-tipografia.png') }}"
                        alt="Agrostock tipografia"
                        class="h-12 w-auto mx-auto mt-6"
                    >
                </div>
            </div>

            <x-auth-session-status class="mb-4" :status="session('status')"/>
            
            <div class="mb-9">
                <p class="text-xs font-semibold tracking-widest uppercase mb-2" style="color:#4caf50;">Acesso ao Sistema</p>
                <h2 class="text-5xl font-bold tracking-tight leading-tight mb-3 sm:text-6xl"
                    style="font-family:'Georgia',serif;color:#1a3d1f;">
                    Bem-vindo<br>de volta
                </h2>
                @if (Route::has('register'))
                <p class="text-sm" style="color:#8a9e8c;">
                    Não tem cadastro?
                    <a href="{{ route('register') }}"
                       class="font-semibold hover:opacity-70 transition-opacity" style="color:#2d6a35;">
                        Crie sua conta grátis
                    </a>
                </p>
                @endif
            </div>

            @php
                $labelClasses = 'block text-sm font-semibold text-gray-900';
                $inputIconClasses = 'w-5 text-gray-900';
                $inputClasses = 'w-full h-14 pl-11 pr-4 py-3 rounded-xl text-base transition-all duration-200 border border-gray-200 bg-gray-50 text-gray-900 focus:outline-none focus:border-green-500 focus:ring-4 focus:ring-green-500/10 focus:bg-white';
                $inputStyle = 'border-color:#d4e8d6;background-color:#f9f6f0;';
            @endphp

            <form wire:submit="login" class="space-y-5 mb-5">

                <!-- Email Address -->
                <div>
                    <label for="email" class="{{ $labelClasses }}">
                        {{ __('E-mail') }}
                    </label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-base pointer-events-none">
                            <x-fas-user class="{{ $inputIconClasses }} h-4"/>
                        </span>
                        <input wire:model="form.email" id="email" name="email" type="email" required autofocus autocomplete="username" 
                               placeholder="seu@email.com" class="{{ $inputClasses }}"
                               style="{{ $inputStyle }}">
                    </div>
                    <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
                </div> 

                <!-- Password -->
                <div x-data="{ showPassword: false }">
                    <label for="password" class="{{ $labelClasses }}">
                        {{ __('Senha') }}
                    </label>
                    <div class="relative">

                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-base pointer-events-none">
                            <x-uni-lock class="{{ $inputIconClasses }} h-5"/>
                        </span>

                        <input wire:model="form.password" id="password" name="password" type="password" required autocomplete="current-password"
                               placeholder="••••••••" class="{{ $inputClasses }}"
                               style="{{ $inputStyle }}"
                               :type="showPassword ? 'text' : 'password'">

                        <button type="button" 
                            @click="showPassword = !showPassword"
                            class="absolute right-3.5 top-1/2 -translate-y-1/2 text-base transition-colors hover:opacity-60"
                            style="color:#8a9e8c;" 
                            tabindex="-1">
                            <x-fas-eye x-show="!showPassword" class="{{ $inputIconClasses }} h-4"/>
                            <x-fas-eye-slash x-show="showPassword" class="{{ $inputIconClasses }} h-4" style="display: none;"/>
                        </button>        
                    </div>
                    <x-input-error :messages="$errors->get('form.password')" class="mt-2" />    
                </div>

                <!-- Remember Me --> 
                <div class="flex items-center justify-between pt-1">
                    <label for="remember" class="inline-flex items-center gap-2 cursor-pointer select-none">
                        <input wire:model="form.remember" id="remember" name="remember" type="checkbox"
                            class="rounded border-gray-300 text-green-600 shadow-sm focus:ring-green-500">
                        <span class="text-sm text-gray-600">{{ __('Lembre-se de mim') }}</span>
                    </label>
                    
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                            class="text-sm text-green-600 font-semibold hover:opacity-70 transition-opacity" style="color:#2d6a35">
                            {{ __('Esqueceu sua senha?') }}
                        </a>
                    @endif    
                </div>
                
                <!-- Submit-->
                <div class="pt-3">
                    <x-primary-button class="w-full block py-4 text-sm">
                        <span class="relative z-10">{{ __('Entrar na plataforma') }}</span>
                        <div class="absolute inset-0 pointer-events-none" style="background:linear-gradient(135deg, rgba(76,175,80,0.2) 0%,transparent 60%);"></div>
                    </x-primary-button>
                </div>    
            </form>

            <div class="flex items-center gap-3 my-5">
                <div class="flex-1 h-px" style="background-color:#d4e8d6;"></div>
            </div>

            <p class="text-center text-sm" style="color:#8a9e8c;">
                Suporte técnico?
                <a href="mailto:suporte@agrostock.com.br"
                   class="font-bold hover:opacity-70 transition-opacity" style="color:#2d6a35;">
                    Fale conosco
                </a>
            </p>
   
        </div>    
    </div>        
</div>
