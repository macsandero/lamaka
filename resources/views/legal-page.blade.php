<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $page->title }} - LAMAKA</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500;600&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
</head>

@php
    $mediaUrl = function (?string $path, ?string $fallback = null): string {
        $path = $path ?: $fallback ?: '';

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://') || str_starts_with($path, '/')) {
            return $path;
        }

        if (str_starts_with($path, 'images/') || str_starts_with($path, 'videos/') || $path === 'logo.png') {
            return asset($path);
        }

        return \Illuminate\Support\Facades\Storage::disk('public')->url($path);
    };
@endphp

<body class="bg-[#f0e8dc] text-[#2f2a24] overflow-x-hidden">
    <header class="paper-header recycled-paper fixed top-0 left-0 right-0 z-50 border-b border-[#d8cdbd]">
        <div class="max-w-7xl mx-auto px-6 md:px-12 py-4 flex items-center justify-between">
            <a href="/" class="flex items-center">
                <img src="{{ data_get($contact, 'footer_logo') ? $mediaUrl(data_get($contact, 'footer_logo')) : '/logo.png' }}" alt="{{ data_get($contact, 'business_name', 'LAMAKA') }}" class="w-32 md:w-44">
            </a>

            <nav class="hidden md:flex items-center gap-10 text-xs font-semibold uppercase tracking-[0.24em] text-[#3f3d32]">
                <a href="/#esperienze" class="border-b border-transparent pb-1 transition duration-300 hover:border-[#3f3d32] hover:text-[#2f2a24]">Esperienze</a>
                <a href="/#chi-siamo" class="border-b border-transparent pb-1 transition duration-300 hover:border-[#3f3d32] hover:text-[#2f2a24]">Chi siamo</a>
                <a href="/#animali" class="border-b border-transparent pb-1 transition duration-300 hover:border-[#3f3d32] hover:text-[#2f2a24]">Animali</a>
            </nav>

            <a href="/#prenota"
               class="hidden md:inline-block border border-[#3f3d32] text-[#3f3d32] px-5 py-3 text-xs font-semibold uppercase tracking-[0.24em] transition duration-300 hover:bg-[#3f3d32] hover:text-[#f3efe7]">
                Prenota
            </a>
        </div>
    </header>

    <main class="pt-[104px]">
        <section class="recycled-paper paper-experiences min-h-[calc(100vh-104px)] px-6 py-24 md:px-12 md:py-32">
            <article class="mx-auto max-w-4xl bg-white/45 border border-[#d8cdbd] px-6 py-10 md:px-12 md:py-14">
                <a href="/" class="inline-block mb-10 text-xs uppercase tracking-[0.25em] text-[#7a6f63] hover:text-[#2f2a24] transition">Torna al sito</a>

                <p class="uppercase tracking-[0.3em] text-xs text-[#7a6f63] mb-4">LAMAKA</p>
                <h1 class="text-5xl md:text-6xl leading-none mb-10" style="font-family:'Cormorant Garamond',serif;">
                    {{ $page->title }}
                </h1>

                <div class="legal-copy">
                    {!! $page->body !!}
                </div>
            </article>
        </section>
    </main>
</body>
</html>
