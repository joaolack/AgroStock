@php
    $supplier = $supplier ?? null;
    $isEdit = (bool) $supplier;
@endphp

@if ($errors->any())
    <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
        <ul class="space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ $isEdit ? route('suppliers.update', $supplier) : route('suppliers.store') }}" method="POST">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    <h2 class="mb-5 text-xs font-bold uppercase tracking-widest" style="color:#4a5c4c;">Informações básicas</h2>

    <div class="space-y-4">
        <div>
            <label for="name" class="mb-1.5 block text-xs font-semibold tracking-wide" style="color:#4a5c4c;">
                Nome da empresa: <span class="text-red-500">*</span>
            </label>
            <input type="text" id="name" name="name" value="{{ old('name', $supplier?->name) }}" required
                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 transition-all duration-200 focus:border-green-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-green-500/10"
                style="border-color:#d4e8d6;background:#f9f6f0;color:#1a3d1f;"
                placeholder="Ex: AgroTech Fornecedora Ltda">
        </div>

        <div>
            <label for="contact_name" class="mb-1.5 block text-xs font-semibold tracking-wide" style="color:#4a5c4c;">
                Nome do contato
            </label>
            <input type="text" id="contact_name" name="contact_name" value="{{ old('contact_name', $supplier?->contact_name) }}"
                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 transition-all duration-200 focus:border-green-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-green-500/10"
                style="border-color:#d4e8d6;background:#f9f6f0;color:#1a3d1f;"
                placeholder="Ex: João Silva">
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <label for="phone" class="mb-1.5 block text-xs font-semibold tracking-wide" style="color:#4a5c4c;">
                    Telefone: <span class="text-red-500">*</span>
                </label>
                <input type="text" id="phone" name="phone" value="{{ old('phone', $supplier?->phone) }}" required
                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 transition-all duration-200 focus:border-green-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-green-500/10"
                    style="border-color:#d4e8d6;background:#f9f6f0;color:#1a3d1f;"
                    placeholder="(00) 00000-0000">
            </div>

            <div>
                <label for="email" class="mb-1.5 block text-xs font-semibold tracking-wide" style="color:#4a5c4c;">
                    E-mail: <span class="text-red-500">*</span>
                </label>
                <input type="email" id="email" name="email" value="{{ old('email', $supplier?->email) }}" required
                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 transition-all duration-200 focus:border-green-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-green-500/10"
                    style="border-color:#d4e8d6;background:#f9f6f0;color:#1a3d1f;"
                    placeholder="contato@empresa.com.br">
            </div>
        </div>
    </div>

    <h2 class="mb-5 mt-7 text-xs font-bold uppercase tracking-widest" style="color:#4a5c4c;">Endereço</h2>

    <div class="space-y-4">
        <div>
            <label for="address" class="mb-1.5 block text-xs font-semibold tracking-wide" style="color:#4a5c4c;">
                Endereço completo: <span class="text-red-500">*</span>
            </label>
            <input type="text" id="address" name="address" value="{{ old('address', $supplier?->address) }}" required
                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 transition-all duration-200 focus:border-green-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-green-500/10"
                style="border-color:#d4e8d6;background:#f9f6f0;color:#1a3d1f;"
                placeholder="Ex: Rua, número, complemento">
        </div>

        <div class="grid gap-4 md:grid-cols-3">
            <div>
                <label for="city" class="mb-1.5 block text-xs font-semibold tracking-wide" style="color:#4a5c4c;">
                    Cidade: <span class="text-red-500">*</span>
                </label>
                <input type="text" id="city" name="city" value="{{ old('city', $supplier?->city) }}" required
                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 transition-all duration-200 focus:border-green-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-green-500/10"
                    style="border-color:#d4e8d6;background:#f9f6f0;color:#1a3d1f;"
                    placeholder="Ex: São Paulo">
            </div>

            <div>
                <label for="state" class="mb-1.5 block text-xs font-semibold tracking-wide" style="color:#4a5c4c;">
                    Estado: <span class="text-red-500">*</span>
                </label>
                <select id="state" name="state" required
                    class="w-full rounded-xl border px-4 py-3 text-sm transition-all duration-200"
                    style="border-color:#d4e8d6;background:#f9f6f0;color:#4a5c4c;">
                    <option value="">Selecione...</option>
                    @foreach (['AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA', 'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN', 'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO'] as $state)
                        <option value="{{ $state }}" {{ old('state', $supplier?->state) === $state ? 'selected' : '' }}>{{ $state }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="zip_code" class="mb-1.5 block text-xs font-semibold tracking-wide" style="color:#4a5c4c;">
                    CEP: <span class="text-red-500">*</span>
                </label>
                <input type="text" id="zip_code" name="zip_code" value="{{ old('zip_code', $supplier?->zip_code) }}" required
                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 transition-all duration-200 focus:border-green-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-green-500/10"
                    style="border-color:#d4e8d6;background:#f9f6f0;color:#1a3d1f;"
                    placeholder="00000-000">
            </div>
        </div>
    </div>

    <h2 class="mb-5 mt-7 text-xs font-bold uppercase tracking-widest" style="color:#4a5c4c;">Observações</h2>

    <div class="space-y-4">
        <div>
            <label for="notes" class="mb-1.5 block text-xs font-semibold tracking-wide" style="color:#4a5c4c;">
                Anotações internas
            </label>
            <textarea id="notes" name="notes" rows="4"
                placeholder="Informações adicionais sobre o fornecedor..."
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-md focus:border-green-500 focus:ring-green-500"
                style="border-color:#d4e8d6;background:#f9f6f0;color:#1a3d1f;">{{ old('notes', $supplier?->notes) }}</textarea>
        </div>

        <label class="flex items-center gap-3">
            <input type="checkbox" name="active" value="1" {{ old('active', $supplier?->active ?? true) ? 'checked' : '' }}
                class="h-5 w-5 rounded text-green-600 focus:ring-green-500">
            <span class="text-sm font-medium text-gray-700">Fornecedor ativo</span>
        </label>
    </div>

    <div class="mt-8 flex gap-4 border-t border-gray-200 pt-4">
        <x-primary-button type="submit" class="px-6 py-3">
            {{ $isEdit ? 'Salvar alterações' : 'Salvar fornecedor' }}
        </x-primary-button>

        <a href="{{ route('suppliers.index') }}"
            class="flex items-center gap-2 rounded-xl border px-4 py-2.5 text-sm font-semibold transition-all hover:-translate-y-px"
            style="border-color:#fca5a5;color:#dc2626;background:#fef2f2;"
            onmouseover="this.style.background='#fee2e2'"
            onmouseout="this.style.background='#fef2f2'">
            Cancelar
        </a>
    </div>
</form>
