@extends('layouts.app')

@section('slot')
<div class="flex flex-1 flex-col min-h-screen overflow-hidden">
    <header class="sticky top-0 z-20 flex items-center justify-between px-6 py-3.5 border-b bg-white/80 backdrop-blur-md"
            style="border-color:#d4e8d6;">
        <div class="flex items-center gap-3">
            <button class="lg:hidden p-2 rounded-lg hover:bg-agro-pale transition colors" style="color:#4a5c4c;">
                ☰
            </button>
            <div>
                <h1 class="font-display text-xl font-bold tracking-tight" style="color:#1a3d1f;">Exportar</h1>
                <p class="text-[11px]" style="color:#8a9e8c;">Exporte seus relátorios</p>
            </div>
        </div>

        <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold text-white cursor-pointer"
                 style="background:linear-gradient(135deg,#4caf50,#2d6a35);">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
        </div>
    </header>

    <div class="flex-1 p-6 overflow-y-auto space-y-5">
        
    </div>
</div>
@endsection