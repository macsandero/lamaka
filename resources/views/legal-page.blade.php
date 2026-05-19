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

    @if ($contact)
        <footer class="recycled-paper paper-footer text-[#2f2a24] px-6 md:px-12 py-20">
            <div class="max-w-7xl mx-auto">
                <div class="grid lg:grid-cols-[1.15fr_.85fr_.95fr] gap-14">
                    <div>
                        <span class="mb-8 inline-block" style="position: relative; line-height: 0; box-shadow: 0 12px 24px rgba(47, 42, 36, .18);">
                            <img src="{{ data_get($contact, 'footer_logo') ? $mediaUrl(data_get($contact, 'footer_logo')) : '/logo.png' }}" alt="{{ data_get($contact, 'business_name', 'LAMAKA') }}" style="display: block; width: 10rem; max-height: 8rem; object-fit: contain; opacity: .9;">
                            <span aria-hidden="true" style="position: absolute; inset: 0; pointer-events: none; box-shadow: inset 0 0 22px rgba(47, 42, 36, .36), inset 0 0 0 1px rgba(47, 42, 36, .18);"></span>
                        </span>
                        <div class="footer-copy max-w-lg text-[#4f4a35] leading-relaxed">
                            {!! data_get($contact, 'footer_body') ?: '<p>Esperienze lente tra Dolomiti, natura e passo calmo. Un luogo per ritrovare tempo, respiro e relazione con gli animali.</p>' !!}
                        </div>

                        @if (data_get($contact, 'whatsapp'))
                            <a href="https://wa.me/{{ preg_replace('/\D+/', '', data_get($contact, 'whatsapp')) }}" class="mt-8 inline-flex h-11 w-11 items-center justify-center rounded-full bg-[#25d366] text-white transition hover:scale-105" aria-label="WhatsApp">
                                <svg class="h-5 w-5" viewBox="0 0 32 32" fill="currentColor" aria-hidden="true">
                                    <path d="M16.02 4.03A11.86 11.86 0 0 0 5.93 22.1L4 29l7.06-1.86a11.83 11.83 0 0 0 4.96 1.09h.01A11.86 11.86 0 0 0 16.02 4.03Zm0 21.99a9.63 9.63 0 0 1-4.9-1.34l-.35-.21-4.18 1.1 1.11-4.08-.23-.38a9.64 9.64 0 1 1 8.55 4.91Zm5.29-7.22c-.29-.15-1.72-.85-1.98-.94-.27-.1-.46-.15-.66.15-.19.29-.76.94-.93 1.13-.17.2-.34.22-.63.08-.29-.15-1.22-.45-2.33-1.43-.86-.77-1.44-1.72-1.61-2.01-.17-.29-.02-.45.13-.59.13-.13.29-.34.44-.51.15-.17.2-.29.29-.49.1-.2.05-.37-.02-.51-.08-.15-.66-1.59-.9-2.18-.24-.57-.48-.49-.66-.5h-.56c-.2 0-.51.07-.78.37-.27.29-1.03 1.01-1.03 2.47s1.06 2.86 1.21 3.06c.15.2 2.08 3.18 5.04 4.46.7.3 1.25.48 1.68.62.71.22 1.35.19 1.86.12.57-.09 1.72-.7 1.96-1.38.24-.68.24-1.26.17-1.38-.07-.13-.26-.2-.55-.35Z"/>
                                </svg>
                            </a>
                        @endif
                    </div>

                    <div>
                        <h2 class="mb-7 text-4xl" style="font-family:'Cormorant Garamond',serif;">{{ data_get($contact, 'heading', 'Contatti') }}</h2>
                        <div class="space-y-3 text-[#2f2a24]/80 leading-relaxed">
                            @if (data_get($contact, 'email'))
                                <p>Email: <a href="mailto:{{ data_get($contact, 'email') }}" class="hover:text-[#f9f4ea]">{{ data_get($contact, 'email') }}</a></p>
                            @endif
                            @if (data_get($contact, 'phone'))
                                <p>Tel: <a href="tel:{{ data_get($contact, 'phone') }}" class="hover:text-[#f9f4ea]">{{ data_get($contact, 'phone') }}</a></p>
                            @endif
                            @if (data_get($contact, 'address'))
                                <p class="whitespace-pre-line">{{ data_get($contact, 'address') }}</p>
                            @endif
                        </div>

                        @if (data_get($contact, 'directions_url'))
                            <a href="{{ data_get($contact, 'directions_url') }}" class="mt-8 inline-block border-b border-[#2f2a24]/45 pb-1 text-xs uppercase tracking-[0.22em] text-[#2f2a24]/80 hover:text-[#f9f4ea]" target="_blank" rel="noreferrer">
                                {{ data_get($contact, 'directions_label', 'Indicazioni stradali') }}
                            </a>
                        @endif
                    </div>

                    <div>
                        <h2 class="mb-7 text-4xl" style="font-family:'Cormorant Garamond',serif;">Seguici</h2>
                        @php
                            $instagramUrl = data_get($contact, 'instagram_url');
                            $instagramNote = data_get($contact, 'instagram_note') ?: data_get($contact, 'footer_note') ?: '<p>LAMAKA nasce per vivere la natura con rispetto, lentezza e attenzione agli animali.</p>';
                        @endphp
                        <div class="mb-8 flex gap-5 text-3xl">
                            @if (data_get($contact, 'facebook_url'))
                                <a href="{{ data_get($contact, 'facebook_url') }}" class="hover:text-[#f9f4ea]" target="_blank" rel="noreferrer" aria-label="Facebook">
                                    <svg class="h-8 w-8" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M14 8.5V6.6c0-.8.2-1.2 1.3-1.2H17V2.2c-.8-.1-1.6-.2-2.4-.2-2.4 0-4.1 1.5-4.1 4.2v2.3H7.7V12h2.8v10H14V12h2.9l.4-3.5H14Z"/></svg>
                                </a>
                            @endif
                            @if ($instagramUrl)
                                <a href="{{ $instagramUrl }}" class="hover:text-[#f9f4ea]" target="_blank" rel="noreferrer" aria-label="Instagram">
                                    <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg>
                                </a>
                            @else
                                <span aria-label="Instagram">
                                    <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg>
                                </span>
                            @endif
                        </div>
                        @if ($instagramNote)
                            <div class="footer-copy text-[#2f2a24]/80 leading-relaxed italic">
                                {!! $instagramNote !!}
                            </div>
                        @endif
                    </div>
                </div>

                <div class="mt-14 border-t border-[#2f2a24]/30 pt-3.5 flex flex-col gap-2.5 text-[11px] uppercase tracking-[0.16em] text-[#2f2a24]/75 md:flex-row md:items-center md:justify-between">
                    <div class="flex flex-wrap gap-7">
                        <a href="{{ data_get($contact, 'privacy_url') ?: route('legal.privacy') }}" class="hover:text-[#f9f4ea]">Privacy policy</a>
                        <a href="{{ data_get($contact, 'cookie_url') ?: route('legal.cookie') }}" class="hover:text-[#f9f4ea]">Cookie policy</a>
                    </div>
                    <div class="normal-case tracking-normal md:text-right">
                        <span>{{ data_get($contact, 'legal_text', '© Copyright LAMAKA') }}</span>
                        @if (data_get($contact, 'company_name'))
                            <span> - {{ data_get($contact, 'company_name') }}</span>
                        @endif
                        @if (data_get($contact, 'tax_code'))
                            <span> - {{ data_get($contact, 'tax_code') }}</span>
                        @endif
                        @if (data_get($contact, 'vat_number'))
                            <span> - {{ data_get($contact, 'vat_number') }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </footer>
    @endif
</body>
</html>
