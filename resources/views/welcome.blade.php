<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LAMAKA</title>

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

    $hasHtml = fn (?string $value): bool => $value !== strip_tags((string) $value);

    $heroTitle = trim((string) data_get($homepage, 'hero_title', 'Passeggiate nella natura con lama e alpaca'));
    $heroTitleLines = preg_split('/\R/u', $heroTitle) ?: [$heroTitle];
    $experienceItems = $experiences->isNotEmpty() ? $experiences : new \Illuminate\Support\Collection([
        ['title' => 'Primo incontro', 'description' => 'Una passeggiata semplice e immersiva per conoscere lama e alpaca, camminando tra lago, sentieri e natura.', 'image' => 'images/esperienze/Foto diAlpaca e lama completa.jpeg'],
        ['title' => 'Passeggiata al tramonto', 'description' => 'Un’esperienza lenta e romantica tra le montagne del Cadore, accompagnati dal ritmo calmo degli animali.', 'image' => 'images/esperienze/due lama al pascolo.jpeg'],
    ]);
    $animalItems = $animals->isNotEmpty() ? $animals : new \Illuminate\Support\Collection([
        ['name' => 'Athos', 'description' => 'Curioso e sempre attento a ciò che succede attorno a lui. Ama osservare le persone e avvicinarsi con delicatezza.', 'image' => 'images/animali/Athos.jpeg'],
        ['name' => 'Kairos', 'description' => 'Dolce e tranquillo, trasmette calma già dal primo incontro. È perfetto per chi cerca un momento di relax autentico.', 'image' => 'images/animali/Kairos.jpeg'],
        ['name' => 'Skiantos', 'description' => 'Il leader del gruppo. Sicuro di sé, curioso e sempre pronto ad aprire la strada durante le passeggiate.', 'image' => 'images/animali/Skiantos-2.jpeg'],
        ['name' => 'Gulliver', 'description' => 'Elegante e riflessivo, ama i ritmi lenti e le passeggiate silenziose immerso nella natura.', 'image' => 'images/animali/Gulliver.jpeg'],
        ['name' => 'Francis', 'description' => 'Affettuoso e socievole, crea subito empatia con adulti e bambini grazie al suo carattere gentile.', 'image' => 'images/animali/Francis.jpeg'],
    ]);
@endphp

<body class="bg-[#f0e8dc] text-[#2f2a24] overflow-x-hidden">

    <header id="site-header" class="paper-header recycled-paper fixed top-0 left-0 right-0 z-50 border-b border-[#d8cdbd]">

        <div class="max-w-7xl mx-auto px-6 md:px-12 py-4 flex items-center justify-between">

            <a href="#" class="flex items-center">
                <img src="{{ data_get($contact, 'footer_logo') ? $mediaUrl(data_get($contact, 'footer_logo')) : '/logo.png' }}" alt="{{ data_get($contact, 'business_name', 'LAMAKA') }}" class="w-32 md:w-44">
            </a>

            <nav class="hidden md:flex items-center gap-10 text-xs font-semibold uppercase tracking-[0.24em] text-[#3f3d32]">
                <a href="/#esperienze" class="js-scroll border-b border-transparent pb-1 transition duration-300 hover:border-[#3f3d32] hover:text-[#2f2a24]">Esperienze</a>
                <a href="/#chi-siamo" class="js-scroll border-b border-transparent pb-1 transition duration-300 hover:border-[#3f3d32] hover:text-[#2f2a24]">Chi siamo</a>
                <a href="/#animali" class="js-scroll border-b border-transparent pb-1 transition duration-300 hover:border-[#3f3d32] hover:text-[#2f2a24]">Animali</a>
            </nav>

            <a href="/#prenota"
               class="js-scroll hidden md:inline-block border border-[#3f3d32] text-[#3f3d32] px-5 py-3 text-xs font-semibold uppercase tracking-[0.24em] transition duration-300 hover:bg-[#3f3d32] hover:text-[#f3efe7]">
                Prenota
            </a>

            <button
                id="mobile-menu-button"
                type="button"
                aria-label="Apri menu"
                aria-expanded="false"
                class="md:hidden flex flex-col gap-1.5"
            >
                <span class="w-6 h-[1px] bg-[#4f4a35]"></span>
                <span class="w-6 h-[1px] bg-[#4f4a35]"></span>
                <span class="w-6 h-[1px] bg-[#4f4a35]"></span>
            </button>

        </div>

        <div id="mobile-menu" class="hidden md:hidden border-t border-[#d8cdbd]">
            <div class="flex flex-col px-6 py-6 gap-6 text-xs uppercase tracking-[0.22em] text-[#5f574d]">
                <a href="/#esperienze" class="js-scroll mobile-link">Esperienze</a>
                <a href="/#chi-siamo" class="js-scroll mobile-link">Chi siamo</a>
                <a href="/#animali" class="js-scroll mobile-link">Animali</a>

                <a href="/#prenota" class="js-scroll mobile-link border border-[#6f6a45] text-[#4f4a35] px-5 py-3 text-center">
                    Prenota
                </a>
            </div>
        </div>

    </header>

    <main class="pt-[104px]">

        <section class="relative h-[calc(100vh-104px)] overflow-hidden bg-[#2f2a24]">
            <video autoplay muted loop playsinline class="absolute inset-0 w-full h-full object-cover scale-[1.03]">
                <source src="{{ $mediaUrl(data_get($homepage, 'hero_video'), 'videos/hero-optimized.mp4') }}" type="video/mp4">
            </video>

            <div class="absolute inset-0 bg-black/30"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-black/55 via-black/10 to-black/10"></div>

            <div class="relative z-10 flex h-full items-end px-6 md:px-12 pb-16 md:pb-24">
                <div class="max-w-3xl">
                    <p class="text-white/75 uppercase tracking-[0.3em] text-xs mb-4">{{ data_get($homepage, 'hero_eyebrow', 'Cadore - Dolomiti') }}</p>

                    <h1 class="text-white text-5xl md:text-7xl leading-none font-light" style="font-family:'Cormorant Garamond',serif;">
                        @foreach ($heroTitleLines as $line)
                            {{ $line }}@if (! $loop->last)<br>@endif
                        @endforeach
                    </h1>

                    <div class="text-white/85 mt-6 text-base md:text-lg max-w-xl leading-relaxed">
                        {!! data_get($homepage, 'hero_subtitle', '<p>Esperienze lente tra lago, boschi e montagne. Un tempo sospeso da vivere insieme ai nostri animali.</p>') !!}
                    </div>

                    <a href="{{ data_get($homepage, 'hero_button_anchor', '#esperienze') }}" class="js-scroll inline-block mt-10 bg-[#f3efe7] text-[#2f2a24] px-8 py-4 uppercase tracking-[0.25em] text-xs hover:bg-white transition duration-500">
                        {{ data_get($homepage, 'hero_button_label', 'Scopri le esperienze') }}
                    </a>
                </div>
            </div>
        </section>

        <section id="esperienze" class="recycled-paper paper-experiences pt-24 pb-58 px-6 md:px-12">
            <div class="max-w-6xl mx-auto">
                <p class="uppercase tracking-[0.3em] text-xs text-[#7a6f63] mb-4">{{ data_get($homepage, 'experiences_eyebrow', 'Esperienze') }}</p>

                <h2 class="text-5xl md:text-6xl mb-16" style="font-family:'Cormorant Garamond',serif;">
                    {{ data_get($homepage, 'experiences_title', 'Natura, lentezza e relazione') }}
                </h2>

                <div class="grid md:grid-cols-2 gap-10">
                    @foreach ($experienceItems as $experience)
                        <a href="{{ $experience instanceof \App\Models\Experience ? route('experiences.show', $experience) : '#esperienze' }}" class="block bg-white/45 p-8 transition duration-500 hover:bg-white/70">
                            <img src="{{ $mediaUrl(data_get($experience, 'image')) }}" alt="{{ data_get($experience, 'title') }}" class="w-full h-[400px] object-cover mb-6">
                            <h3 class="text-3xl mb-4" style="font-family:'Cormorant Garamond',serif;">{{ data_get($experience, 'title') }}</h3>
                            <div class="experience-copy text-[#5f574d] leading-relaxed">
                                @if ($hasHtml(data_get($experience, 'description')))
                                    {!! data_get($experience, 'description') !!}
                                @else
                                    {!! nl2br(e(data_get($experience, 'description'))) !!}
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>

        <section id="chi-siamo" class="recycled-paper paper-about py-32 px-6 md:px-12">
            <div class="max-w-7xl mx-auto grid md:grid-cols-2 gap-16 items-center">
                <div>
                    <p class="uppercase tracking-[0.3em] text-xs text-[#7a6f63] mb-4">{{ data_get($homepage, 'about_eyebrow', 'Chi siamo') }}</p>

                    <h2 class="text-5xl md:text-6xl mb-8" style="font-family:'Cormorant Garamond',serif;">
                        {{ data_get($homepage, 'about_title', 'Un tempo lento da condividere') }}
                    </h2>

                    <div class="space-y-6 text-[#5f574d] leading-relaxed text-lg">
                        {!! data_get($homepage, 'about_body', '<p>LAMAKA nasce dal desiderio di creare esperienze autentiche nella natura, accompagnati dal passo lento e silenzioso di lama e alpaca.</p><p>Tra boschi, montagne e paesaggi del Cadore, ogni passeggiata diventa un’occasione per rallentare, respirare e ritrovare una connessione semplice con gli animali e con il territorio.</p><p>Non una semplice attività turistica, ma un’esperienza da vivere insieme.</p>') !!}
                    </div>
                </div>

                <div>
                    <img src="{{ $mediaUrl(data_get($homepage, 'about_image'), 'images/about/chi-siamo.jpeg') }}" alt="Chi siamo - LAMAKA" class="w-full h-[700px] object-cover">
                </div>
            </div>
        </section>

        <section id="animali" class="recycled-paper paper-animals py-32 px-6 md:px-12">
            <div class="max-w-7xl mx-auto">
                <p class="uppercase tracking-[0.3em] text-xs text-[#7a6f63] mb-4">{{ data_get($homepage, 'animals_eyebrow', 'Gli animali') }}</p>

                <h2 class="text-5xl md:text-6xl mb-20 whitespace-pre-line" style="font-family:'Cormorant Garamond',serif;">{{ data_get($homepage, 'animals_title', 'Cinque personalità, un solo passo lento') }}</h2>

                <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-10">
                    @foreach ($animalItems as $animal)
                        <div class="bg-white/50 p-6">
                            <img src="{{ $mediaUrl(data_get($animal, 'image')) }}" alt="{{ data_get($animal, 'name') }}" class="w-full h-[500px] object-cover mb-6">
                            <h3 class="text-3xl mb-3" style="font-family:'Cormorant Garamond',serif;">{{ data_get($animal, 'name') }}</h3>
                            <p class="text-[#5f574d] leading-relaxed">{{ data_get($animal, 'description') }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="recycled-paper paper-booking pt-40 pb-[21vh] px-6 md:px-12">
            <div id="prenota" class="max-w-7xl mx-auto grid scroll-mt-[calc(104px+7rem)] lg:grid-cols-[.85fr_1.15fr] gap-16">
                <div>
                    <p class="uppercase tracking-[0.3em] text-xs text-[#7a6f63] mb-4">{{ data_get($bookingSettings, 'eyebrow', 'Prenota') }}</p>
                    <h2 class="text-5xl md:text-6xl mb-8" style="font-family:'Cormorant Garamond',serif;">
                        {{ data_get($bookingSettings, 'heading', 'Prenota la tua esperienza') }}
                    </h2>
                    <p class="text-[#5f574d] leading-relaxed text-lg max-w-2xl">
                        {{ data_get($bookingSettings, 'body', 'Compila il modulo con le informazioni principali. Ti ricontatteremo per confermare disponibilità, dettagli e orari.') }}
                    </p>

                    @if (data_get($bookingSettings, 'image'))
                        <img src="{{ $mediaUrl(data_get($bookingSettings, 'image')) }}" alt="Prenota LAMAKA" class="mt-10 h-[360px] w-full max-w-2xl object-cover">
                    @endif
                </div>

                <form method="POST" action="{{ route('booking.store') }}" class="booking-form bg-white/55 border border-[#d8cdbd] p-6 md:p-10">
                    @csrf
                    <input type="text" name="website" tabindex="-1" autocomplete="off" class="hidden">

                    @if (session('booking_success'))
                        <div class="mb-8 border border-[#6f6a45]/30 bg-[#f3efe7] px-5 py-4 text-[#4f4a35] leading-relaxed">
                            {{ session('booking_success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-8 border border-red-300 bg-red-50 px-5 py-4 text-red-800 leading-relaxed">
                            Controlla i campi evidenziati e riprova.
                        </div>
                    @endif

                    <div class="grid md:grid-cols-2 gap-6">
                        @foreach ($bookingFields as $field)
                            @php
                                $fieldName = "fields[{$field->key}]";
                                $oldValue = old("fields.{$field->key}", request($field->key));
                                $inputType = $field->type === 'datetime' ? 'datetime-local' : $field->type;
                                $minimumValue = match ($field->type) {
                                    'date' => now()->addDay()->toDateString(),
                                    'datetime' => now()->addDay()->startOfDay()->format('Y-m-d\TH:i'),
                                    default => null,
                                };
                                $selectOptions = $field->optionsList();

                                if ($field->key === 'esperienza') {
                                    $selectOptions = collect($selectOptions)
                                        ->merge(collect($experienceItems)->map(fn ($experience) => data_get($experience, 'title')))
                                        ->filter()
                                        ->unique(fn ($option) => \Illuminate\Support\Str::lower(trim($option)))
                                        ->sortBy(fn ($option) => \Illuminate\Support\Str::lower(trim($option)))
                                        ->values()
                                        ->all();

                                    $selectOptions = collect($selectOptions)
                                        ->reject(fn ($option) => \Illuminate\Support\Str::lower(trim($option)) === 'altro')
                                        ->concat(
                                            collect($selectOptions)
                                                ->filter(fn ($option) => \Illuminate\Support\Str::lower(trim($option)) === 'altro')
                                        )
                                        ->values()
                                        ->all();
                                }

                                $inputClasses = 'w-full min-w-0 max-w-full border border-[#d8cdbd] bg-white/80 px-4 py-3 text-[#2f2a24] outline-none focus:border-[#6f6a45] transition';
                            @endphp

                            <div class="min-w-0 {{ $field->type === 'datetime' ? 'booking-datetime-field' : '' }} {{ $field->is_full_width || in_array($field->type, ['textarea', 'checkbox'], true) ? 'md:col-span-2' : '' }}">
                                @if ($field->type === 'checkbox')
                                    <label class="flex gap-3 text-[#5f574d] leading-relaxed">
                                        <input type="checkbox" name="{{ $fieldName }}" value="1" @checked($oldValue) class="mt-1 h-5 w-5 border-[#d8cdbd] text-[#6f6a45] focus:ring-[#6f6a45]">
                                        <span>
                                            @if ($field->key === 'privacy' && str_contains($field->label, 'Informativa Privacy'))
                                                @php
                                                    [$privacyLabelBefore, $privacyLabelAfter] = explode('Informativa Privacy', $field->label, 2);
                                                @endphp
                                                {{ $privacyLabelBefore }}<a href="{{ route('legal.privacy') }}" class="border-b border-[#6f6a45]/45 text-[#4f4a35] transition hover:text-[#2f2a24]" target="_blank">Informativa Privacy</a>{{ $privacyLabelAfter }}
                                            @else
                                                {{ $field->label }}
                                            @endif
                                            @if ($field->is_required)<span class="text-red-700">*</span>@endif
                                        </span>
                                    </label>
                                @else
                                    <label class="block uppercase tracking-[0.25em] text-xs text-[#7a6f63] mb-2" for="booking-{{ $field->key }}">
                                        {{ $field->label }}@if ($field->is_required)<span class="text-red-700">*</span>@endif
                                    </label>

                                    @if ($field->type === 'textarea')
                                        <textarea id="booking-{{ $field->key }}" name="{{ $fieldName }}" rows="5" placeholder="{{ $field->placeholder }}" class="{{ $inputClasses }}">{{ $oldValue }}</textarea>
                                    @elseif ($field->type === 'select')
                                        <select id="booking-{{ $field->key }}" name="{{ $fieldName }}" class="{{ $inputClasses }}">
                                            <option value="">Seleziona</option>
                                            @foreach ($selectOptions as $option)
                                                <option value="{{ $option }}" @selected($oldValue === $option)>{{ $option }}</option>
                                            @endforeach
                                        </select>
                                    @else
                                        <input id="booking-{{ $field->key }}" type="{{ $inputType }}" name="{{ $fieldName }}" value="{{ $oldValue }}" placeholder="{{ $field->placeholder }}" @if ($minimumValue) min="{{ $minimumValue }}" @endif class="{{ $inputClasses }} {{ $field->type === 'datetime' ? 'booking-datetime-input' : '' }}">
                                    @endif
                                @endif

                                @error("fields.{$field->key}")
                                    <p class="mt-2 text-sm text-red-800">{{ $message }}</p>
                                @enderror

                                @if ($field->help_text)
                                    <p class="mt-2 text-sm text-[#7a6f63]">{{ $field->help_text }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <button type="submit" class="mt-8 w-full bg-[#6f6a45] text-white px-8 py-4 uppercase tracking-[0.25em] text-xs hover:bg-[#4f4a35] transition duration-500">
                        {{ data_get($bookingSettings, 'submit_label', 'Invia richiesta') }}
                    </button>
                </form>
            </div>
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
                        <div class="footer-copy max-w-lg text-[#2f2a24]/80 leading-relaxed">
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

    <script>
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');

        mobileMenuButton.addEventListener('click', () => {
            const isHidden = mobileMenu.classList.toggle('hidden');
            mobileMenuButton.setAttribute('aria-expanded', String(!isHidden));
        });

        document.querySelectorAll('.mobile-link').forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.add('hidden');
                mobileMenuButton.setAttribute('aria-expanded', 'false');
            });
        });

        const scrollToHash = (hash, behavior = 'smooth') => {
            if (!hash) return false;

            const target = document.querySelector(hash);
            const header = document.querySelector('#site-header');
            if (!target || !header) return false;

            const headerHeight = header.offsetHeight + (hash === '#prenota' ? 112 : 0);
            const targetTop = target.getBoundingClientRect().top + window.scrollY;

            window.scrollTo({
                top: targetTop - headerHeight,
                behavior,
            });

            return true;
        };

        document.querySelectorAll('.js-scroll').forEach((link) => {
            link.addEventListener('click', function (event) {
                const linkUrl = new URL(this.getAttribute('href'), window.location.href);
                const currentUrl = new URL(window.location.href);

                if (linkUrl.pathname !== currentUrl.pathname || !linkUrl.hash) return;

                if (scrollToHash(linkUrl.hash)) {
                    event.preventDefault();
                    history.pushState(null, '', linkUrl.hash);
                }
            });
        });

        if (window.location.hash) {
            window.addEventListener('load', () => {
                setTimeout(() => scrollToHash(window.location.hash, 'auto'), 50);
            });
        }
    </script>

</body>
</html>
