@props(['type' => 'success'])

@php
    $config = match($type) {
        'success' => [
            'bg' => 'bg-green-500',
            'icon' => '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>'
        ],
        'error' => [
            'bg' => 'bg-red-500',
            'icon' => '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>'
        ],
        default => [
            'bg' => 'bg-gray-500',
            'icon' => '<path d="M10 2a8 8 0 100 16 8 8 0 000-16z"/>'
        ]
    };
@endphp

<div 
    x-data="{ show: true }" 
    x-show="show" 
    x-init="setTimeout(() => show = false, 5000)"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-x-full"
    x-transition:enter-end="opacity-100 translate-x-0"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100 translate-x-0"
    x-transition:leave-end="opacity-0 translate-x-full"
    class="fixed top-20 right-4 z-50 max-w-sm w-full shadow-lg rounded-lg {{ $config['bg'] }} text-white">
    <div class="p-4 flex items-start">
        <svg class="h-6 w-6 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            {!! $config['icon'] !!}
        </svg>
        <div class="ml-3 flex-1">
            {{ $slot }} 
        </div>
        <button @click="show = false" class="ml-4 flex-shrink-0 hover:opacity-75">
            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
            </svg>
        </button>
    </div>
</div>