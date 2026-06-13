@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'form-input mt-1 block w-full rounded-lg border-gray-500 shadow-md focus:border-blue-500 focus:ring-blue-500 sm:text-sm']) }}>
