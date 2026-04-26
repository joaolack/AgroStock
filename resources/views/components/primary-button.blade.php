<button {{ $attributes->merge(['type' => 'submit', 'class' => 'relative  mt-2 py-3.5 text-white rounded-xl text-sm font-semibold tracking-wide overflow-hidden transition-all duration-200  hover:-translate-y-px active:translate-y-0 focus:outline-none focus:ring-4 focus:ring-green-500/30', 'style' => 'background-color:#1a3d1f;box-shadow:none;', 'onmouseover' => "this.style.backgroundColor='#2d6a35';this.style.boxShadow='0 6px 24px rgba(26,61,31,0.28)'", 'onmouseout' => "this.style.backgroundColor='#1a3d1f';this.style.boxShadow='none'" ]) }}>
    {{ $slot }}
</button>
