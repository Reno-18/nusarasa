<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-8 py-3 bg-white border-2 border-nusarasa-dark rounded-pill font-black text-xs text-nusarasa-dark uppercase tracking-widest hover:bg-nusarasa-cream transition-all duration-150 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]']) }}>
    {{ $slot }}
</button>
