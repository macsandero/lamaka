<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
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

    $versionedMediaUrl = function (?string $path, ?string $fallback = null) use ($mediaUrl, $experience): string {
        $url = $mediaUrl($path, $fallback);

        if (! $path || str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $url;
        }

        return $url.(str_contains($url, '?') ? '&' : '?').'v='.$experience->updated_at?->timestamp;
    };

    $hasHtml = fn (?string $value): bool => $value !== strip_tags((string) $value);
@endphp

<body class="bg-[#f0e8dc] text-[#2f2a24] overflow-x-hidden">
    <header class="paper-header recycled-paper fixed top-0 left-0 right-0 z-50 border-b border-[#d8cdbd]">
        <div class="max-w-7xl mx-auto px-6 md:px-12 py-4 flex items-center justify-between">
            <a href="/" class="flex items-center">
                <img src="/logo.png" alt="LAMAKA" class="w-28 md:w-36">
            </a>

            <nav class="hidden md:flex items-center gap-10 text-xs uppercase tracking-[0.22em] text-[#5f574d]">
                <a href="/#esperienze" class="hover:text-[#2f2a24] transition">{{ __('site.experiences') }}</a>
                <a href="/#chi-siamo" class="hover:text-[#2f2a24] transition">{{ __('site.about') }}</a>
                <a href="/#animali" class="hover:text-[#2f2a24] transition">{{ __('site.animals') }}</a>
                <a href="/#contatti" class="hover:text-[#2f2a24] transition">{{ __('site.contacts') }}</a>
                <a href="{{ route('language.switch','it') }}">🇮🇹</a><a href="{{ route('language.switch','en') }}">🇬🇧</a>
            </nav>

            <a href="/#prenota" class="hidden md:inline-block border border-[#6f6a45] text-[#4f4a35] px-5 py-3 text-xs uppercase tracking-[0.22em] hover:bg-[#6f6a45] hover:text-white transition duration-500">
                {{ __('site.book') }}
            </a>

            <a href="{{ url('/').'/?esperienza='.rawurlencode($experience->title).'#prenota' }}" class="md:hidden border border-[#6f6a45] text-[#4f4a35] px-4 py-3 text-[10px] uppercase tracking-[0.2em]">
                {{ __('site.book') }}
            </a>
        </div>
    </header>

    <main class="pt-[104px]">
        <section class="recycled-paper paper-experiences py-24 md:py-32 px-6 md:px-12">
            <div class="max-w-6xl mx-auto">
                <a href="/#esperienze" class="inline-block mb-10 text-xs uppercase tracking-[0.25em] text-[#7a6f63] hover:text-[#2f2a24] transition">{{ __('site.back_experiences') }}</a>

                <div class="grid lg:grid-cols-[.95fr_1.05fr] gap-14 items-start">
                    <div>
                        <p class="uppercase tracking-[0.3em] text-xs text-[#7a6f63] mb-4">{{ __('site.experience') }}</p>
                        <h1 class="text-5xl md:text-6xl leading-none mb-8" style="font-family:'Cormorant Garamond',serif;">
                            {{ $experience->title }}
                        </h1>

                        @if ($experience->image)
                            <img src="{{ $versionedMediaUrl($experience->image) }}" alt="{{ $experience->title }}" class="mb-10 block w-full min-h-[320px] max-h-[560px] object-cover">
                        @endif

                        @if ($experience->description)
                            <div class="experience-copy text-[#5f574d] text-lg leading-relaxed mb-10">
                                @if ($hasHtml($experience->description))
                                    {!! $experience->description !!}
                                @else
                                    {!! nl2br(e($experience->description)) !!}
                                @endif
                            </div>
                        @endif

                    </div>

                    <div class="bg-white/55 border border-[#d8cdbd] p-6 md:p-10">
                        @if ($experience->experience_type)
                            <div class="mb-8">
                                <p class="experience-label text-xl md:text-2xl mb-3" style="font-family:'Cormorant Garamond',serif;">{{ __('site.experience_type') }}</p>
                                <div class="experience-copy text-[#2f2a24] text-xl leading-relaxed">
                                    @if ($hasHtml($experience->experience_type))
                                        {!! $experience->experience_type !!}
                                    @else
                                        {!! nl2br(e($experience->experience_type)) !!}
                                    @endif
                                </div>
                            </div>
                        @endif

                        @if ($experience->purpose)
                            <div class="mb-8">
                                <p class="experience-label text-xl md:text-2xl mb-3" style="font-family:'Cormorant Garamond',serif;">{{ __('site.purpose') }}</p>
                                <div class="experience-copy text-[#2f2a24] text-xl leading-relaxed">
                                    @if ($hasHtml($experience->purpose))
                                        {!! $experience->purpose !!}
                                    @else
                                        {!! nl2br(e($experience->purpose)) !!}
                                    @endif
                                </div>
                            </div>
                        @endif

                        @if ($experience->experience_details)
                            <div class="mb-8">
                                <p class="experience-label text-xl md:text-2xl mb-3" style="font-family:'Cormorant Garamond',serif;">{{ __('site.during') }}</p>
                                <div class="experience-copy text-[#2f2a24] text-xl leading-relaxed">
                                    @if ($hasHtml($experience->experience_details))
                                        {!! $experience->experience_details !!}
                                    @else
                                        <ul>
                                            @foreach ($experience->detailsList() as $detail)
                                                <li>{{ $detail }}</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            </div>
                        @endif

                        @foreach ([
                            ['duration' => $experience->short_duration, 'price' => $experience->short_price],
                            ['duration' => $experience->long_duration, 'price' => $experience->long_price],
                            ['duration' => $experience->third_duration, 'price' => $experience->third_price],
                            ['duration' => $experience->fourth_duration, 'price' => $experience->fourth_price],
                        ] as $option)
                            @if ($option['duration'] || $option['price'])
                                <div class="mb-8 text-xl leading-relaxed">
                                    @if ($option['duration'])
                                        <p><span class="experience-label">{{ __('site.duration') }}</span> {{ $option['duration'] }}</p>
                                    @endif
                                    @if ($option['price'])
                                        <p><span class="experience-label">{{ __('site.price') }}</span> <strong>{{ $option['price'] }}</strong></p>
                                    @endif
                                </div>
                            @endif
                        @endforeach

                        @if ($experience->durationNotesList())
                            <div class="mb-8 text-xl leading-relaxed">
                                <p class="experience-label mb-3" style="font-family:'Cormorant Garamond',serif;">{{ __('site.notes') }}</p>
                                <ul class="experience-copy list-disc space-y-2 pl-6 text-[#2f2a24] text-xl leading-relaxed">
                                    @foreach ($experience->durationNotesList() as $note)
                                        <li>{{ $note }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if ($experience->ideal_for)
                            <div class="text-xl leading-relaxed">
                                <p class="experience-label mb-3" style="font-family:'Cormorant Garamond',serif;">{{ __('site.ideal_for') }}</p>
                                <div class="experience-copy text-[#2f2a24] text-xl leading-relaxed">
                                    @if ($hasHtml($experience->ideal_for))
                                        {!! $experience->ideal_for !!}
                                    @else
                                        {!! nl2br(e($experience->ideal_for)) !!}
                                    @endif
                                </div>
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
