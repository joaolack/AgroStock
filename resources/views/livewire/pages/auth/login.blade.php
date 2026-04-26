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

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="min-h-screen grid grid-cols-1 lg:grid-cols-2 font-sans">
    <div class="relative hidden lg:flex flex-col justify-between p-12 overflow-hidden" 
         style="background-color:#1a3d1f;">
        
        <!--radial glows--> 
        <div class="absolute inset-0 pointer-event-none">
            <div class="absolute w-96 h-96 rounded-full"
                 style="background:radial-gradient(ellipse, rgba(76,175,80,0.18) 0%,transparent 70%);top: -80px;right: -80px;"></div>
            <div class="absolute w-64 h-64 rounded-full" 
                 style="background:radial-gradient(ellipse,rgba(168,213,171,0.12) 0%,transparent 70%);bottom:-60px;left:-60px;"></div>
        </div>

        <div class="absolute rounded-full pointer-events-none"
             style="width:440px;height:440px;top:-130px;right:-130px;border:1px solid rgba(168,213,171,0.1);"></div>
        <div class="absolute rounded-full pointer-events-none"
             style="width:260px;height:260px;top:50px;right:-50px;border:1px solid rgba(168,213,171,0.1);"></div>
        <div class="absolute rounded-full pointer-events-none"
             style="width:320px;height:320px;bottom:-90px;left:-90px;border:1px solid rgba(168,213,171,0.08);"></div>

        
        <div class="relative z-10 flex items-center gap-3">
            <div class="w-11 h-11 rounded-xl flex items-center justify-center text-xl"
                 style="background-color:#4caf50;">🌾</div>
            <span class="text-xl font-bold text-white tracking-tight"
                  style="font-family:'Georgia',serif;">
                Agro<span style="color:#4caf50;">Stock</span>
            </span>
        </div>

        <!--Hero principal-->
        <div class="relative z-10 flex-1 flex flex-col justify-center py-10">
            <div class="inline-flex items-center gap-2 rounded-full px-4 py-1.5 w-fit mb-7" 
                 style="background:rgba(76,175,80,0.15);border:1px solid rgba(76,175,80,0.3);">
                <span class="w-1.5 h-1.5 rounded-full animate-pulse" style="background:#4caf50;"></span>
                <span class="text-xs font-semibold tracking-widest uppercase" style="color:#a8d5ab;">Gestão de Estoque</span> 
            </div>

            <h1 class="text-5xl font-bold text-white leading-tight tracking-tight mb-5" 
                style="font-family:'Georgia',serif;">
                Controle total<br> do seu <em class="font-light italic" style="color:#a8d5ab;">agronegócio</em>
            </h1>

            <p class="text-sm leading-relaxed max-w-xs mb-10" style="color:rgba(255,255,255,0.5);">
                Gerencie insumos, sementes, defensivos e equipamentos em uma plataforma integrada, desenhada para o seu negócio.
            </p>
        </div>

        <!--Features-->
        <div class="relative z-10 flex flex-col gap-2.5">
            @foreach([['📦','Controle de estoque em tempo real'],['📊','Relatórios e análises automáticas'],['🔔','Alertas de validade e reposição']] as [$icon, $text])
            <div class="flex items-center gap-3 rounded-xl px-4 py-3"
                 style="background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.08);">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center text-sm shrink-0"
                     style="background:rgba(76,175,80,0.2);">{{ $icon }}</div>
                <span class="text-sm font-medium" style="color:rgba(255,255,255,0.65);">{{ $text }}</span>
            </div>
            @endforeach
        </div>
    </div>    
 


    <div class="relative flex items-center justify-center p-8 sm:p-12 bg-white dark:bg-gray-900 overflow-hidde">    
        
        <div class="absolute top-0 right-0 w-72 h-72 pointer-events-none" 
             style="background:radial-gradient(ellipse at top right,#eef7ef 0%,transparent 70%);"></div>

        <div class="relative z-10 w-full max-w-sm">

            <!--Mobile-->
            <div class="lg:hidden text-center mb-8">
                <div class="inline-flex items-center gap-2 mb-2">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center text-lg"
                         style="background-color:#4caf50;">🌾</div>
                    <span class="text-xl font-bold tracking-tight" style="font-family:'Georgia',serif;color:#1a3d1f;">
                        Agro<span style="color:#4caf50;">Stock</span>
                    </span>     
                </div>
            </div>


            <x-auth-session-status class="mb-4" :status="session('status')"/>
            

            <div class="mb-12">
                <p class="text-xs font-semibold tracking-widest uppercase mb-2" style="color:#4caf50;">Acesso ao Sistema</p>
                <h2 class="text-4xl font-bold tracking-tight leading-tight mb-2 dark:text-white"
                    style="font-family:'Georgia',serif;color:#1a3d1f;">
                    Bem-vindo<br>de volta
                </h2>
                @if (Route::has('register'))
                <p class="text-sm" style="color:#8a9e8c;">
                    Não tem cadastro?
                    <a href="{{ route('register') }}" wire:navigate
                       class="font-semibold hover:opacity-70 transition-opacity" style="color:#2d6a35;">
                        Crie sua conta grátis
                    </a>
                </p>
                @endif
            </div>


            <form wire:submit="login" class="space-y-4 mb-4">

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-900 dark:text-gray-100">
                        {{ __('E-mail') }}
                    </label>
                    <div class="relative">
                        <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-base pointer-events-none">
                            <x-fas-user class="w-5 h-4 text-gray-900 dark:text-gray-400"/> 
                        </span>
                        <input wire:model="form.email" id="email" name="email" type="email" required autofocus autocomplete="username" 
                               placeholder="seu@email.com" class="w-full pl-10 pr-4 py-3 rounded-xl text-sm transition-all duration-200 
                                    border border-gray-200 bg-gray-50 text-gray-900
                                    focus:outline-none focus:border-green-500 focus:ring-4 focus:ring-green-500/10 focus:bg-white
                                    dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:placeholder-gray-500
                                    dark:focus:border-green-500 dark:focus:bg-gray-800" 
                               style="border-color:#d4e8d6;background-color:#f9f6f0;">
                    </div>
                    <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
                </div> 

                <!-- Password -->
                <div x-data="{ showPassword: false }">
                    <label for="password" class="block text-sm font-medium text-gray-900 dark:text-gray-100">
                        {{ __('Senha') }}
                    </label>
                    <div class="relative">

                        <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-base pointer-events-none">
                            <x-uni-lock class="w-5 h-5 text-gray-900 dark:text-gray-400"/>
                        </span>

                        <input wire:model="form.password" id="password" name="password" type="password" required autocomplete="current-password"
                               placeholder="••••••••" class="w-full pl-10 pr-4 py-3 rounded-xl text-sm transition-all duration-200 
                                    border border-gray-200 bg-gray-50 text-gray-900
                                    focus:outline-none focus:border-green-500 focus:ring-4 focus:ring-green-500/10 focus:bg-white
                                    dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:placeholder-gray-500
                                    dark:focus:border-green-500 dark:focus:bg-gray-800" 
                               style="border-color:#d4e8d6;background-color:#f9f6f0;"
                               :type="showPassword ? 'text' : 'password'">

                        <button type="button" 
                            @click="showPassword = !showPassword"
                            class="absolute right-3.5 top-1/2 -translate-y-1/2 text-base transition-colors hover:opacity-60"
                            style="color:#8a9e8c;" 
                            tabindex="-1">
                            <x-fas-eye x-show="!showPassword" class="w-5 h-4 text-gray-900 dark:text-gray-400"/>
                            <x-fas-eye-slash x-show="showPassword" class="w-5 h-4 text-gray-900 dark:text-gray-400" style="display: none;"/>
                        </button>        
                    </div>
                    <x-input-error :messages="$errors->get('form.password')" class="mt-2" />    
                </div>

                <!-- Remember Me --> 
                <div class="flex items-center justify-between pt-1">
                    <label for="remember" class="inline-flex items-center gap-2 cursor-pointer select-none">
                        <input wire:model="form.remember" id="remember" name="remember" type="checkbox"
                            class="rounded border-gray-300 text-green-600 shadow-sm focus:ring-green-500 
                            dark:bg-gray-900 dark:border-gray-700 dark:focus:ring-green-600">
                        <span class="text-sm text-gray-600 dark:text-gray-400">{{ __('Lembre-se de mim') }}</span>
                    </label>
                    
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" wire:navigate 
                            class="text-sm text-green-600 font-semibold hover:opacity-70 transition-opacity" style="color:#2d6a35">
                            {{ __('Esqueceu sua senha?') }}
                        </a>
                    @endif    
                </div>
                
                <!-- Submit-->
                <x-primary-button class="w-full">
                    <span class="relative z-10">{{ __('Entrar na plataforma') }}</span>
                    <div class="absolute inset-0 pointer-events-none" style="background:linear-gradient(135deg, rgba(76,175,80,0.2) 0%,transparent 60%);"></div>
                </x-primary-button>
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
