<button
    type="button"
    x-data
    @click="$dispatch('open-mobile-navigation')"
    class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border transition lg:hidden"
    style="border-color:#d4e8d6;color:#4a5c4c;background:#fff;"
    aria-label="Abrir menu"
>
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M4 12h16M4 17h16"/>
    </svg>
</button>
