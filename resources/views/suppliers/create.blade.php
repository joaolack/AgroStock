@extends('layouts.app')

@section('slot')
<div class="flex min-h-screen flex-1 flex-col overflow-hidden">
    <header class="sticky top-0 z-20 flex items-center justify-between border-b bg-white/85 px-5 py-3.5 backdrop-blur-md sm:px-6"
        style="border-color:#d4e8d6;">
        <div class="flex min-w-0 items-center gap-3">
            <a href="{{ route('suppliers.index') }}"
                class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-xl border bg-white text-sm shadow-sm transition-all duration-200 hover:-translate-x-0.5 hover:bg-green-50"
                style="border-color:#d4e8d6;color:#2d6a35;"
                aria-label="Voltar para fornecedores">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="h-4 w-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </a>

            <div class="min-w-0">
                <p class="text-[11px] font-bold uppercase tracking-[0.22em]" style="color:#6e876f;">Suprimentos</p>
                <h1 class="truncate text-xl font-bold tracking-tight sm:text-2xl" style="color:#1a3d1f;">Cadastrar fornecedor</h1>
            </div>
        </div>

        <div class="flex items-center gap-2.5">
            <div class="hidden rounded-full border px-3 py-1.5 text-xs font-semibold sm:block"
                style="border-color:#d4e8d6;color:#4a5c4c;background:#f9f6f0;">
                Novo parceiro
            </div>
            <div class="flex h-8 w-8 cursor-pointer items-center justify-center rounded-full text-sm font-bold text-white"
                style="background:linear-gradient(135deg,#4caf50,#2d6a35);">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
        </div>
    </header>

    <div class="flex-1 overflow-y-auto p-4 sm:p-6">
        <div class="mx-auto grid max-w-7xl gap-5 xl:grid-cols-[minmax(0,1fr)_360px]">
            <main class="overflow-hidden rounded-2xl border bg-white shadow-sm"
                style="border-color:#d4e8d6;box-shadow:0 18px 45px rgba(26,61,31,0.08);">
                <div class="border-b px-5 py-5 sm:px-6"
                    style="border-color:#d4e8d6;background:linear-gradient(135deg,#ffffff 0%,#f6fbf4 100%);">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                        <div class="max-w-2xl">
                            <div class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-bold uppercase tracking-[0.18em]"
                                style="background:#eaf6e9;color:#2d6a35;">
                                <span class="h-1.5 w-1.5 rounded-full" style="background:#4caf50;"></span>
                                Cadastro
                            </div>
                            <h2 class="mt-4 text-2xl font-bold tracking-tight sm:text-3xl" style="color:#142f18;">
                                Dados comerciais e localização
                            </h2>
                            <p class="mt-2 max-w-xl text-sm leading-6" style="color:#6e876f;">
                                Registre a empresa, contato, endereço e informações internas para vincular fornecedores aos produtos.
                            </p>
                        </div>

                        <div class="grid grid-cols-3 overflow-hidden rounded-xl border bg-white text-center"
                            style="border-color:#d4e8d6;">
                            <div class="px-4 py-3">
                                <p class="text-[10px] font-bold uppercase tracking-[0.16em]" style="color:#8a9e8c;">Etapa</p>
                                <p class="mt-1 text-lg font-bold" style="color:#1a3d1f;">01</p>
                            </div>
                            <div class="border-x px-4 py-3" style="border-color:#d4e8d6;">
                                <p class="text-[10px] font-bold uppercase tracking-[0.16em]" style="color:#8a9e8c;">Tipo</p>
                                <p class="mt-1 text-lg font-bold" style="color:#1a3d1f;">Fornecedor</p>
                            </div>
                            <div class="px-4 py-3">
                                <p class="text-[10px] font-bold uppercase tracking-[0.16em]" style="color:#8a9e8c;">Status</p>
                                <p class="mt-1 text-lg font-bold" style="color:#1a3d1f;">Ativo</p>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="mx-5 mt-5 rounded-xl border px-4 py-3 sm:mx-6"
                        style="border-color:#fecaca;background:#fff7f7;color:#991b1b;">
                        <div class="flex items-start gap-3">
                            <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg"
                                style="background:#fee2e2;color:#b91c1c;">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="h-4 w-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold">Revise os campos destacados</p>
                                <ul class="mt-1 list-disc space-y-1 pl-4 text-sm">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <form action="{{ route('suppliers.store') }}" method="POST" class="px-5 py-6 sm:px-6">
                    @csrf

                    <section>
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl"
                                style="background:#eaf6e9;color:#2d6a35;">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="h-5 w-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 18H9" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14" />
                                    <circle cx="17" cy="18" r="2" />
                                    <circle cx="7" cy="18" r="2" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base font-bold" style="color:#1a3d1f;">Informações comerciais</h3>
                                <p class="text-xs" style="color:#8a9e8c;">Empresa, contato e canais principais</p>
                            </div>
                        </div>

                        <div class="mt-5 grid grid-cols-1 gap-5 lg:grid-cols-2">
                            <div class="lg:col-span-2">
                                <label for="name" class="mb-1.5 block text-xs font-bold uppercase tracking-[0.14em]" style="color:#4a5c4c;">
                                    Nome da empresa <span class="text-red-600">*</span>
                                </label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}"
                                    class="block w-full rounded-xl border-gray-300 bg-white text-sm text-slate-900 shadow-sm transition focus:border-green-600 focus:ring-green-600 @error('name') border-red-500 @enderror"
                                    placeholder="Ex: AgroTech Fornecedora Ltda" required>
                                @error('name') <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="contact_name" class="mb-1.5 block text-xs font-bold uppercase tracking-[0.14em]" style="color:#4a5c4c;">
                                    Nome do contato
                                </label>
                                <input type="text" id="contact_name" name="contact_name" value="{{ old('contact_name') }}"
                                    class="block w-full rounded-xl border-gray-300 bg-white text-sm text-slate-900 shadow-sm transition focus:border-green-600 focus:ring-green-600 @error('contact_name') border-red-500 @enderror"
                                    placeholder="Ex: Jo&atilde;o Silva">
                                @error('contact_name') <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="phone" class="mb-1.5 block text-xs font-bold uppercase tracking-[0.14em]" style="color:#4a5c4c;">
                                    Telefone <span class="text-red-600">*</span>
                                </label>
                                <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                                    class="block w-full rounded-xl border-gray-300 bg-white text-sm text-slate-900 shadow-sm transition focus:border-green-600 focus:ring-green-600 @error('phone') border-red-500 @enderror"
                                    placeholder="(00) 00000-0000" required>
                                @error('phone') <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div class="lg:col-span-2">
                                <label for="email" class="mb-1.5 block text-xs font-bold uppercase tracking-[0.14em]" style="color:#4a5c4c;">
                                    E-mail <span class="text-red-600">*</span>
                                </label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}"
                                    class="block w-full rounded-xl border-gray-300 bg-white text-sm text-slate-900 shadow-sm transition focus:border-green-600 focus:ring-green-600 @error('email') border-red-500 @enderror"
                                    placeholder="contato@empresa.com.br" required>
                                @error('email') <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </section>

                    <section class="mt-8 border-t pt-8" style="border-color:#d4e8d6;">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl"
                                style="background:#eff6ff;color:#1d4ed8;">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="h-5 w-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s7-4.35 7-11a7 7 0 1 0-14 0c0 6.65 7 11 7 11Z" />
                                    <circle cx="12" cy="10" r="2.5" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base font-bold" style="color:#1a3d1f;">Endereço</h3>
                                <p class="text-xs" style="color:#8a9e8c;">Localização operacional do fornecedor</p>
                            </div>
                        </div>

                        <div class="mt-5 grid grid-cols-1 gap-5 lg:grid-cols-6">
                            <div class="lg:col-span-6">
                                <label for="address" class="mb-1.5 block text-xs font-bold uppercase tracking-[0.14em]" style="color:#4a5c4c;">
                                    Endereço completo <span class="text-red-600">*</span>
                                </label>
                                <input type="text" id="address" name="address" value="{{ old('address') }}"
                                    class="block w-full rounded-xl border-gray-300 bg-white text-sm text-slate-900 shadow-sm transition focus:border-green-600 focus:ring-green-600 @error('address') border-red-500 @enderror"
                                    placeholder="Ex: Rua, número, complemento" required>
                                @error('address') <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div class="lg:col-span-3">
                                <label for="city" class="mb-1.5 block text-xs font-bold uppercase tracking-[0.14em]" style="color:#4a5c4c;">
                                    Cidade <span class="text-red-600">*</span>
                                </label>
                                <input type="text" id="city" name="city" value="{{ old('city') }}"
                                    class="block w-full rounded-xl border-gray-300 bg-white text-sm text-slate-900 shadow-sm transition focus:border-green-600 focus:ring-green-600 @error('city') border-red-500 @enderror"
                                    placeholder="Ex: S&atilde;o Paulo" required>
                                @error('city') <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div class="lg:col-span-1">
                                <label for="state" class="mb-1.5 block text-xs font-bold uppercase tracking-[0.14em]" style="color:#4a5c4c;">
                                    UF <span class="text-red-600">*</span>
                                </label>
                                <select id="state" name="state"
                                    class="block w-full rounded-xl border-gray-300 bg-white text-sm text-slate-900 shadow-sm transition focus:border-green-600 focus:ring-green-600 @error('state') border-red-500 @enderror"
                                    required>
                                    <option value="">UF</option>
                                    @foreach (['AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA', 'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN', 'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO'] as $state)
                                        <option value="{{ $state }}" {{ old('state') === $state ? 'selected' : '' }}>{{ $state }}</option>
                                    @endforeach
                                </select>
                                @error('state') <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div class="lg:col-span-2">
                                <label for="zip_code" class="mb-1.5 block text-xs font-bold uppercase tracking-[0.14em]" style="color:#4a5c4c;">
                                    CEP <span class="text-red-600">*</span>
                                </label>
                                <input type="text" id="zip_code" name="zip_code" value="{{ old('zip_code') }}"
                                    class="block w-full rounded-xl border-gray-300 bg-white text-sm text-slate-900 shadow-sm transition focus:border-green-600 focus:ring-green-600 @error('zip_code') border-red-500 @enderror"
                                    placeholder="00000-000" required>
                                @error('zip_code') <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </section>

                    <section class="mt-8 border-t pt-8" style="border-color:#d4e8d6;">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl"
                                style="background:#fff7ed;color:#c2410c;">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="h-5 w-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h8M8 12h8M8 17h5" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base font-bold" style="color:#1a3d1f;">Observações</h3>
                                <p class="text-xs" style="color:#8a9e8c;">Anotações internas e status de uso</p>
                            </div>
                        </div>

                        <div class="mt-5 space-y-5">
                            <div>
                                <label for="notes" class="mb-1.5 block text-xs font-bold uppercase tracking-[0.14em]" style="color:#4a5c4c;">
                                    Anotações internas
                                </label>
                                <textarea id="notes" name="notes" rows="4"
                                    class="block w-full rounded-xl border-gray-300 bg-white text-sm text-slate-900 shadow-sm transition focus:border-green-600 focus:ring-green-600 @error('notes') border-red-500 @enderror"
                                    placeholder="Informa&ccedil;&otilde;es adicionais sobre o fornecedor...">{{ old('notes') }}</textarea>
                                @error('notes') <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <label class="flex items-center justify-between gap-4 rounded-xl border px-4 py-3"
                                style="border-color:#d4e8d6;background:#fbfdfb;">
                                <span>
                                    <span class="block text-sm font-bold text-slate-900">Fornecedor ativo</span>
                                    <span class="mt-0.5 block text-xs text-slate-500">Disponível para vincular a produtos e consultas.</span>
                                </span>
                                <input type="checkbox" name="active" value="1" {{ old('active', true) ? 'checked' : '' }}
                                    class="h-5 w-5 rounded border-gray-300 text-green-700 focus:ring-green-600">
                            </label>
                        </div>
                    </section>

                    <div class="mt-8 flex flex-col-reverse gap-3 border-t pt-5 sm:flex-row sm:items-center sm:justify-end"
                        style="border-color:#d4e8d6;">
                        <a href="{{ route('suppliers.index') }}"
                            class="inline-flex items-center justify-center gap-2 rounded-xl border px-5 py-3 text-sm font-bold transition-all duration-200 hover:bg-red-50"
                            style="border-color:#fecaca;color:#991b1b;">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 6 6 18M6 6l12 12" />
                            </svg>
                            Cancelar
                        </a>
                        <button type="submit"
                            class="inline-flex items-center justify-center gap-2 rounded-xl px-5 py-3 text-sm font-bold text-white shadow-lg transition-all duration-200 hover:-translate-y-px hover:shadow-xl"
                            style="background:#1a3d1f;box-shadow:0 12px 24px rgba(26,61,31,0.18);"
                            onmouseover="this.style.background='#2d6a35'"
                            onmouseout="this.style.background='#1a3d1f'">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 6 9 17l-5-5" />
                            </svg>
                            Salvar fornecedor
                        </button>
                    </div>
                </form>
            </main>

            <aside class="space-y-5">

                <div class="rounded-2xl border bg-white p-5 shadow-sm" style="border-color:#d4e8d6;">
                    <h3 class="text-sm font-bold uppercase tracking-[0.16em]" style="color:#1a3d1f;">Checklist do cadastro</h3>
                    <div class="mt-4 space-y-3">
                        <div class="flex items-center gap-3 rounded-xl border px-3 py-3" style="border-color:#edf4ee;background:#fbfdfb;">
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg" style="background:#eaf6e9;color:#2d6a35;">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="h-4 w-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7 10 17l-5-5" />
                                </svg>
                            </span>
                            <div>
                                <p class="text-sm font-semibold text-slate-900">Contato principal</p>
                                <p class="text-xs text-slate-500">Empresa, telefone e e-mail</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 rounded-xl border px-3 py-3" style="border-color:#edf4ee;background:#fbfdfb;">
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg" style="background:#eff6ff;color:#1d4ed8;">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="h-4 w-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s7-4.35 7-11a7 7 0 1 0-14 0c0 6.65 7 11 7 11Z" />
                                    <circle cx="12" cy="10" r="2.5" />
                                </svg>
                            </span>
                            <div>
                                <p class="text-sm font-semibold text-slate-900">Endereço completo</p>
                                <p class="text-xs text-slate-500">Cidade, UF e CEP para referência</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 rounded-xl border px-3 py-3" style="border-color:#edf4ee;background:#fbfdfb;">
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg" style="background:#fff7ed;color:#c2410c;">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="h-4 w-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h8M8 12h8M8 17h5" />
                                </svg>
                            </span>
                            <div>
                                <p class="text-sm font-semibold text-slate-900">Notas internas</p>
                                <p class="text-xs text-slate-500">Condições, prazos ou observações</p>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</div>
@endsection
