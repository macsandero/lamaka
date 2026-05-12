<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $experience->title }} - LAMAKA</title>

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

<body class="bg-[#f3efe7] text-[#2f2a24] overflow-x-hidden">
    <header class="fixed top-0 left-0 right-0 z-50 border-b border-[#d8cdbd]" style="background-color:#f3efe7;background-image:radial-gradient(rgba(120,98,72,.035) .7px,transparent .7px),radial-gradient(rgba(120,98,72,.025) .7px,#f3efe7 .7px);background-size:18px 18px;background-position:0 0,9px 9px;">
        <div class="max-w-7xl mx-auto px-6 md:px-12 py-4 flex items-center justify-between">
            <a href="/" class="flex items-center">
                <img src="/logo.png" alt="LAMAKA" class="w-28 md:w-36">
            </a>

            <nav class="hidden md:flex items-center gap-10 text-xs uppercase tracking-[0.22em] text-[#5f574d]">
                <a href="/#esperienze" class="hover:text-[#2f2a24] transition">Esperienze</a>
                <a href="/#chi-siamo" class="hover:text-[#2f2a24] transition">Chi siamo</a>
                <a href="/#animali" class="hover:text-[#2f2a24] transition">Animali</a>
                <a href="/#contatti" class="hover:text-[#2f2a24] transition">Contatti</a>
            </nav>

            <a href="/#prenota" class="hidden md:inline-block border border-[#6f6a45] text-[#4f4a35] px-5 py-3 text-xs uppercase tracking-[0.22em] hover:bg-[#6f6a45] hover:text-white transition duration-500">
                Prenota
            </a>

            <a href="{{ url('/').'/?esperienza='.rawurlencode($experience->title).'#prenota' }}" class="md:hidden border border-[#6f6a45] text-[#4f4a35] px-4 py-3 text-[10px] uppercase tracking-[0.2em]">
                Prenota
            </a>
        </div>
    </header>

    <main class="pt-[104px]">
        <section class="py-24 md:py-32 px-6 md:px-12" style="background-color:#f3efe7;background-image:radial-gradient(rgba(120,98,72,.035) .7px,transparent .7px),radial-gradient(rgba(120,98,72,.025) .7px,#f3efe7 .7px);background-size:18px 18px;background-position:0 0,9px 9px;">
            <div class="max-w-6xl mx-auto">
                <a href="/#esperienze" class="inline-block mb-10 text-xs uppercase tracking-[0.25em] text-[#7a6f63] hover:text-[#2f2a24] transition">Torna alle esperienze</a>

                <div class="grid lg:grid-cols-[.95fr_1.05fr] gap-14 items-start">
                    <div>
                        <p class="uppercase tracking-[0.3em] text-xs text-[#7a6f63] mb-4">Esperienza</p>
                        <h1 class="text-5xl md:text-6xl leading-none mb-8" style="font-family:'Cormorant Garamond',serif;">
                            {{ $experience->title }}
                        </h1>

                        @if ($experience->description)
                            <p class="text-[#5f574d] text-lg leading-relaxed mb-10">{{ $experience->description }}</p>
                        @endif

                        @if ($experience->image)
                            <img src="{{ $mediaUrl($experience->image) }}" alt="{{ $experience->title }}" class="w-full h-[520px] object-cover">
                        @endif
                    </div>

                    <div class="bg-white/55 border border-[#d8cdbd] p-6 md:p-10">
                        @if ($experience->experience_type)
                            <div class="mb-8">
                                <p class="text-red-700 text-xl md:text-2xl font-semibold mb-2" style="font-family:'Cormorant Garamond',serif;">Tipo esperienza</p>
                                <p class="text-[#2f2a24] text-xl leading-relaxed font-semibold">{{ $experience->experience_type }}</p>
                            </div>
                        @endif

                        @if ($experience->purpose)
                            <div class="mb-8">
                                <p class="text-red-700 text-xl md:text-2xl font-semibold mb-2" style="font-family:'Cormorant Garamond',serif;">Finalità</p>
                                <p class="text-[#2f2a24] text-xl leading-relaxed whitespace-pre-line">{{ $experience->purpose }}</p>
                            </div>
                        @endif

                        @if ($experience->experience_details)
                            <div class="mb-8">
                                <p class="text-red-700 text-xl md:text-2xl font-semibold mb-2" style="font-family:'Cormorant Garamond',serif;">Durante l’esperienza</p>
                                <p class="text-[#2f2a24] text-xl leading-relaxed mb-4">Durante l’esperienza:</p>
                                <ul class="list-disc pl-8 space-y-2 text-xl leading-relaxed">
                                    @foreach ($experience->detailsList() as $detail)
                                        <li>{{ $detail }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if ($experience->short_duration || $experience->short_price)
                            <div class="mb-8 text-xl leading-relaxed">
                                @if ($experience->short_duration)
                                    <p><span class="text-red-700 font-semibold">Durata</span> {{ $experience->short_duration }}</p>
                                @endif
                                @if ($experience->short_price)
                                    <p><span class="text-red-700 font-semibold">Prezzo</span> <strong>{{ $experience->short_price }}</strong></p>
                                @endif
                            </div>
                        @endif

                        @if ($experience->long_duration || $experience->long_price)
                            <div class="mb-8 text-xl leading-relaxed">
                                @if ($experience->long_duration)
                                    <p><span class="text-red-700 font-semibold">Durata</span> {{ $experience->long_duration }}</p>
                                @endif
                                @if ($experience->long_price)
                                    <p><span class="text-red-700 font-semibold">Prezzo</span> <strong>{{ $experience->long_price }}</strong></p>
                                @endif
                            </div>
                        @endif

                        @if ($experience->ideal_for)
                            <div class="text-xl leading-relaxed">
                                <p><span class="text-red-700 font-semibold">Ideale per</span> {{ $experience->ideal_for }}</p>
                            </div>
                        @endif

                        <a href="{{ url('/').'/?esperienza='.rawurlencode($experience->title).'#prenota' }}" class="mt-10 inline-block w-full bg-[#6f6a45] text-center text-white px-8 py-4 uppercase tracking-[0.25em] text-xs hover:bg-[#4f4a35] transition duration-500">
                            Prenota questa esperienza
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
