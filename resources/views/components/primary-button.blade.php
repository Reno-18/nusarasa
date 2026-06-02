<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-10 py-4 bg-nusarasa-dark border-2 border-nusarasa-dark rounded-pill font-black text-xs text-white uppercase tracking-widest hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-none transition-all duration-150 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]']) }}>
    {{ $slot }}
</button>
