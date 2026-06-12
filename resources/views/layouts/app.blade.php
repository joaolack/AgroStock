<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'AgroStock') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
         <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
        <style>
            [data-navigation-wrapper] {
                display: contents;
            }
            [x-cloak] { display: none !important; }
        </style>
    </head>

    <body class="font-sans antialiased" style="background:#f9f6f0;">
        <div class="flex min-h-screen">
            <livewire:layout.navigation data-navigation-wrapper/>
            
            <div class="flex-1 flex flex-col min-h-screen overflow-y-auto">
                <!-- Page Content -->
                <main class="flex-1">
                    @yield('slot')
                </main>
            </div>
        </div>

        {{-- Toast --}}
        @if (session('success'))
            <x-toast type="success">{{ session('success') }}</x-toast>
        @endif

        @if (session('error'))
            <x-toast type="error">{{ session('error') }}</x-toast>
        @endif

        @livewireScripts
        @stack('scripts')

    </body>
</html>
