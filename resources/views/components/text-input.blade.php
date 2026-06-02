@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'bg-nusarasa-cream border-2 border-nusarasa-dark focus:ring-0 focus:border-nusarasa-dark rounded-pill px-6 py-4 font-bold placeholder-gray-400']) }}>
