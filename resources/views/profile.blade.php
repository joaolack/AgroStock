@extends('layouts.app')

@section('slot')
@php
    $user = auth()->user();
    $initials = collect(explode(' ', trim($user->name)))
        ->filter()
        ->take(2)
        ->map(fn ($part) => mb_substr($part, 0, 1))
        ->implode('');
    $isVerified = filled($user->email_verified_at);
@endphp

<div class="flex-1 flex flex-col min-h-screen overflow-hidden">
    <header class="sticky top-0 z-20 flex items-center justify-between px-6 py-3.5 border-b bg-white/80 backdrop-blur-md"
            style="border-color:#d4e8d6;">
        <div class="flex items-center gap-3">
            <x-mobile-menu-button />
            <div>
                <h1 class="font-display text-xl font-bold tracking-tight" style="color:#1a3d1f;">Perfil</h1>
                <p class="text-[11px]" style="color:#8a9e8c;">Gerencie os dados de acesso da sua conta</p>
            </div>
        </div>

        <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold text-white cursor-pointer"
                 style="background:linear-gradient(135deg,#4caf50,#2d6a35);">
                {{ strtoupper($initials) }}
            </div>
        </div>
    </header>

    <div class="flex-1 p-6 overflow-y-auto">
        <div class="grid grid-cols-1 xl:grid-cols-[360px_minmax(0,1fr)] gap-6">
            <aside class="space-y-5">
                <section class="bg-white rounded-2xl border overflow-hidden animate-fadeIn" style="border-color:#d4e8d6;">
                    <div class="relative p-6" style="background:linear-gradient(135deg,#f6fbf6 0%,#ffffff 55%,#f9f6f0 100%);">
                        <div class="relative flex items-start gap-4">
                            <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-xl font-bold text-white shadow-sm"
                                 style="background:linear-gradient(135deg,#1a3d1f,#4caf50);">
                                {{ strtoupper($initials) }}
                            </div>
                            <div class="min-w-0 pt-1">
                                <p class="text-[10px] font-semibold uppercase tracking-[0.22em]" style="color:#8a9e8c;">Conta AgroStock</p>
                                <h2 class="mt-1 font-display text-xl font-bold truncate" style="color:#1a3d1f;">{{ $user->name }}</h2>
                                <p class="text-sm truncate" style="color:#4a5c4c;">{{ $user->email }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-5 space-y-3">
                        <div class="flex items-center justify-between rounded-xl border px-4 py-3" style="border-color:#d4e8d6;background:#fbfdfb;">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wide" style="color:#8a9e8c;">Status do e-mail</p>
                                <p class="mt-0.5 text-sm font-semibold" style="color:#1a3d1f;">
                                    {{ $isVerified ? 'Verificado' : 'Pendente' }}
                                </p>
                            </div>
                            <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-bold"
                                  style="background:{{ $isVerified ? '#dcfce7' : '#fef3c7' }};color:{{ $isVerified ? '#166534' : '#92400e' }};">
                                {{ $isVerified ? 'OK' : 'Ação' }}
                            </span>
                        </div>

                        <div class="rounded-xl border px-4 py-3" style="border-color:#d4e8d6;background:#fbfdfb;">
                            <p class="text-xs font-semibold uppercase tracking-wide" style="color:#8a9e8c;">Conta criada em</p>
                            <p class="mt-1 text-sm font-semibold" style="color:#1a3d1f;">
                                {{ optional($user->created_at)->format('d/m/Y') ?? 'Data indisponível' }}
                            </p>
                        </div>

                        <div class="rounded-xl px-4 py-3" style="background:#eef7ef;color:#4a5c4c;">
                            <p class="text-sm leading-6">
                                Mantenha seus dados atualizados para preservar a rastreabilidade das operações e a segurança do acesso.
                            </p>
                        </div>
                    </div>
                </section>

                <section class="bg-white rounded-2xl border p-5 animate-fadeIn" style="border-color:#d4e8d6;animation-delay:0.06s;">
                    <h3 class="font-display text-base font-bold" style="color:#1a3d1f;">Checklist de segurança</h3>
                    <div class="mt-4 space-y-3">
                        <div class="flex items-center gap-3">
                            <span class="h-8 w-8 rounded-lg flex items-center justify-center text-sm font-bold" style="background:#dcfce7;color:#166534;">1</span>
                            <p class="text-sm" style="color:#4a5c4c;">Dados pessoais revisados</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="h-8 w-8 rounded-lg flex items-center justify-center text-sm font-bold" style="background:#eef7ef;color:#2d6a35;">2</span>
                            <p class="text-sm" style="color:#4a5c4c;">Senha forte e exclusiva</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="h-8 w-8 rounded-lg flex items-center justify-center text-sm font-bold" style="background:#fee2e2;color:#b91c1c;">3</span>
                            <p class="text-sm" style="color:#4a5c4c;">Exclusão apenas em caso definitivo</p>
                        </div>
                    </div>
                </section>
            </aside>

            <div class="space-y-5">
                <div class="bg-white rounded-2xl border overflow-hidden animate-fadeIn" style="border-color:#d4e8d6;animation-delay:0.1s;">
                    <div class="p-5 sm:p-6">
                        <livewire:profile.update-profile-information-form />
                    </div>
                </div>

                <div class="bg-white rounded-2xl border overflow-hidden animate-fadeIn" style="border-color:#d4e8d6;animation-delay:0.16s;">
                    <div class="p-5 sm:p-6">
                        <livewire:profile.update-password-form />
                    </div>
                </div>

                <div class="bg-white rounded-2xl border overflow-hidden animate-fadeIn" style="border-color:#fecaca;animation-delay:0.22s;">
                    <div class="p-5 sm:p-6">
                        <livewire:profile.delete-user-form />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
