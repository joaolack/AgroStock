@if ($paginator->hasPages())
<div class="flex items-center justify-between px-5 py-3.5 border-t" style="border-color:#d4e8d6;">

    {{-- "Exibindo X–Y de Z produtos" --}}
    <p class="text-xs" style="color:#8a9e8c;">
        Exibindo
        <span class="font-semibold" style="color:#4a5c4c;">
            {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }}
        </span>
        de
        <span class="font-semibold" style="color:#4a5c4c;">
            {{ $paginator->total() }}
        </span>
        produtos
    </p>

    {{-- Botões de navegação --}}
    <div class="flex items-center gap-1">

        {{-- Anterior --}}
        @if ($paginator->onFirstPage())
            <span class="w-8 h-8 rounded-lg flex items-center justify-center text-sm opacity-30 cursor-not-allowed"
                  style="color:#4a5c4c;">‹</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}"
               class="w-8 h-8 rounded-lg flex items-center justify-center text-sm transition-colors"
               style="color:#4a5c4c;"
               onmouseover="this.style.background='#eef7ef'" onmouseout="this.style.background=''">‹</a>
        @endif

        {{-- Páginas --}}
        @foreach ($elements as $element)

            {{-- Reticências --}}
            @if (is_string($element))
                <span class="w-8 h-8 flex items-center justify-center text-xs"
                      style="color:#8a9e8c;">...</span>
            @endif

            {{-- Números --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="w-8 h-8 rounded-lg flex items-center justify-center text-sm font-semibold text-white"
                              style="background:#1a3d1f;">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}"
                           class="w-8 h-8 rounded-lg flex items-center justify-center text-sm transition-colors"
                           style="color:#4a5c4c;"
                           onmouseover="this.style.background='#eef7ef'" onmouseout="this.style.background=''">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif

        @endforeach

        {{-- Próxima --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}"
               class="w-8 h-8 rounded-lg flex items-center justify-center text-sm transition-colors"
               style="color:#4a5c4c;"
               onmouseover="this.style.background='#eef7ef'" onmouseout="this.style.background=''">›</a>
        @else
            <span class="w-8 h-8 rounded-lg flex items-center justify-center text-sm opacity-30 cursor-not-allowed"
                  style="color:#4a5c4c;">›</span>
        @endif

    </div>
</div>
@endif