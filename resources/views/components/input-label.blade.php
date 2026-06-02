@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-black text-[10px] uppercase tracking-widest text-nusarasa-dark opacity-60 mb-2']) }}>
    {{ $value ?? $slot }}
</label>
