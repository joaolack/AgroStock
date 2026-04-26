@extends('layouts.app')

@section('slot')
<div class="flex min-h-screen flex-1 flex-col overflow-hidden">
    <header class="sticky top-0 z-20 flex items-center justify-between border-b bg-white/80 px-6 py-3.5 backdrop-blur-md"
        style="border-color:#d4e8d6;">
        <div class="flex items-center gap-3">
            <a href="{{ route('suppliers.index') }}"
                class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-sm transition-colors hover:bg-green-100"
                style="color:#4a5c4c;">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="h-4 w-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="font-display text-xl font-bold tracking-tight" style="color:#1a3d1f;">Editar Fornecedor</h1>
                <p class="text-[11px]" style="color:#8a9e8c;">Atualize os dados de {{ $supplier->name }}</p>
            </div>
        </div>

        <div class="flex items-center gap-2.5">
            <div class="flex h-8 w-8 cursor-pointer items-center justify-center rounded-full text-sm font-bold text-white"
                style="background:linear-gradient(135deg,#4caf50,#2d6a35);">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
        </div>
    </header>

    <div class="flex-1 overflow-y-auto p-6">
        <div class="mx-auto max-w-2xl space-y-5">
            <div class="overflow-hidden rounded-2xl border bg-white p-6" style="border-color:#d4e8d6;">
                @include('suppliers.partials.form', ['supplier' => $supplier])
            </div>
        </div>
    </div>
</div>
@endsection
