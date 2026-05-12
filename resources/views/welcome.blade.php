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

    $heroTitle = data_get($homepage, 'hero_title', 'Passeggiate nella natura con lama e alpaca');
    $heroTitleLines = explode("\n", wordwrap($heroTitle, 31, "\n"));
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
    $mapQuery = data_get($contact, 'map_query') ?: data_get($contact, 'address') ?: 'Cadore Dolomiti';
    $mapUrl = 'https://www.google.com/maps?q='.rawurlencode($mapQuery).'&output=embed';
@endphp

<body class="bg-[#f3efe7] text-[#2f2a24] overflow-x-hidden">

    <header id="site-header" class="fixed top-0 left-0 right-0 z-50 border-b border-[#d8cdbd]" style="background-color:#f3efe7;background-image:radial-gradient(rgba(120,98,72,.035) .7px,transparent .7px),radial-gradient(rgba(120,98,72,.025) .7px,#f3efe7 .7px);background-size:18px 18px;background-position:0 0,9px 9px;">

        <div class="max-w-7xl mx-auto px-6 md:px-12 py-4 flex items-center justify-between">

            <a href="#" class="flex items-center">
                <img src="/logo.png" alt="LAMAKA" class="w-28 md:w-36">
            </a>

            <nav class="hidden md:flex items-center gap-10 text-xs uppercase tracking-[0.22em] text-[#5f574d]">
                <a href="/#esperienze" class="js-scroll hover:text-[#2f2a24] transition">Esperienze</a>
                <a href="/#chi-siamo" class="js-scroll hover:text-[#2f2a24] transition">Chi siamo</a>
                <a href="/#animali" class="js-scroll hover:text-[#2f2a24] transition">Animali</a>
                <a href="/#contatti" class="js-scroll hover:text-[#2f2a24] transition">Contatti</a>
            </nav>

            <a href="/#prenota"
               class="js-scroll hidden md:inline-block border border-[#6f6a45] text-[#4f4a35] px-5 py-3 text-xs uppercase tracking-[0.22em] hover:bg-[#6f6a45] hover:text-white transition duration-500">
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
                <a href="/#contatti" class="js-scroll mobile-link">Contatti</a>

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

        <section id="esperienze" class="pt-24 pb-58 px-6 md:px-12" style="background-color:#f3efe7;background-image:radial-gradient(rgba(120,98,72,.035) .7px,transparent .7px),radial-gradient(rgba(120,98,72,.025) .7px,#f3efe7 .7px);background-size:18px 18px;background-position:0 0,9px 9px;">
            <div class="max-w-6xl mx-auto">
                <p class="uppercase tracking-[0.3em] text-xs text-[#7a6f63] mb-4">{{ data_get($homepage, 'experiences_eyebrow', 'Esperienze') }}</p>

                <h2 class="text-5xl md:text-6xl mb-16" style="font-family:'Cormorant Garamond',serif;">
                    {{ data_get($homepage, 'experiences_title', 'Natura, lentezza e relazione') }}
                </h2>

                <div class="grid md:grid-cols-2 gap-10">
                    @foreach ($experienceItems as $experience)
                        <div class="bg-white/45 p-8">
                            <img src="{{ $mediaUrl(data_get($experience, 'image')) }}" alt="{{ data_get($experience, 'title') }}" class="w-full h-[400px] object-cover mb-6">
                            <h3 class="text-3xl mb-4" style="font-family:'Cormorant Garamond',serif;">{{ data_get($experience, 'title') }}</h3>
                            <p class="text-[#5f574d] leading-relaxed">{{ data_get($experience, 'description') }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section id="chi-siamo" class="py-32 px-6 md:px-12" style="background-color:#f3efe7;background-image:radial-gradient(rgba(120,98,72,.035) .7px,transparent .7px),radial-gradient(rgba(120,98,72,.025) .7px,#f3efe7 .7px);background-size:18px 18px;background-position:0 0,9px 9px;">
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

        <section id="animali" class="py-32 px-6 md:px-12" style="background-color:#efe7da;background-image:radial-gradient(rgba(120,98,72,.03) .7px,transparent .7px),radial-gradient(rgba(120,98,72,.02) .7px,#efe7da .7px);background-size:18px 18px;background-position:0 0,9px 9px;">
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

        <section id="contatti" class="min-h-[calc(100vh-104px)] py-32 px-6 md:px-12" style="background-color:#f3efe7;background-image:radial-gradient(rgba(120,98,72,.035) .7px,transparent .7px),radial-gradient(rgba(120,98,72,.025) .7px,#f3efe7 .7px);background-size:18px 18px;background-position:0 0,9px 9px;">
            <div class="max-w-7xl mx-auto grid lg:grid-cols-[.85fr_1.15fr] gap-16 items-start">
                <div>
                    <div class="mb-12">
                        <p class="uppercase tracking-[0.3em] text-xs text-[#7a6f63] mb-4">Contatti</p>
                        <h2 class="text-5xl md:text-6xl mb-8" style="font-family:'Cormorant Garamond',serif;">
                            {{ data_get($contact, 'heading', 'Contatti') }}
                        </h2>
                        <p class="text-[#5f574d] leading-relaxed text-lg max-w-2xl">
                            {{ data_get($contact, 'body', 'Trovi LAMAKA in Cadore, tra Dolomiti, natura e passo lento. Per informazioni puoi scriverci o raggiungerci dai nostri canali.') }}
                        </p>
                    </div>

                    <div class="grid sm:grid-cols-2 lg:grid-cols-1 gap-8 text-[#5f574d]">
                        @if (data_get($contact, 'address'))
                            <div>
                                <p class="uppercase tracking-[0.25em] text-xs text-[#7a6f63] mb-2">Dove</p>
                                <p class="leading-relaxed whitespace-pre-line">{{ data_get($contact, 'address') }}</p>
                            </div>
                        @endif

                        @if (data_get($contact, 'email'))
                            <div>
                                <p class="uppercase tracking-[0.25em] text-xs text-[#7a6f63] mb-2">Email</p>
                                <a href="mailto:{{ data_get($contact, 'email') }}" class="hover:text-[#2f2a24] transition">{{ data_get($contact, 'email') }}</a>
                            </div>
                        @endif

                        @if (data_get($contact, 'phone'))
                            <div>
                                <p class="uppercase tracking-[0.25em] text-xs text-[#7a6f63] mb-2">Telefono</p>
                                <a href="tel:{{ data_get($contact, 'phone') }}" class="hover:text-[#2f2a24] transition">{{ data_get($contact, 'phone') }}</a>
                            </div>
                        @endif

                        @if (data_get($contact, 'whatsapp'))
                            <div>
                                <p class="uppercase tracking-[0.25em] text-xs text-[#7a6f63] mb-2">WhatsApp</p>
                                <a href="https://wa.me/{{ preg_replace('/\D+/', '', data_get($contact, 'whatsapp')) }}" class="hover:text-[#2f2a24] transition">{{ data_get($contact, 'whatsapp') }}</a>
                            </div>
                        @endif

                        @if (data_get($contact, 'instagram_url'))
                            <div>
                                <p class="uppercase tracking-[0.25em] text-xs text-[#7a6f63] mb-2">Instagram</p>
                                <a href="{{ data_get($contact, 'instagram_url') }}" class="hover:text-[#2f2a24] transition">Apri profilo</a>
                            </div>
                        @endif
                    </div>
                </div>

                <iframe
                    src="{{ $mapUrl }}"
                    title="Dove siamo - LAMAKA"
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    class="min-h-[520px] w-full border border-[#d8cdbd] bg-white/50"
                ></iframe>
            </div>
        </section>

        <section class="relative py-32 px-6 md:px-12" style="background-color:#efe7da;background-image:radial-gradient(rgba(120,98,72,.03) .7px,transparent .7px),radial-gradient(rgba(120,98,72,.02) .7px,#efe7da .7px);background-size:18px 18px;background-position:0 0,9px 9px;">
            <span id="prenota" class="absolute top-32 block h-px w-px overflow-hidden"></span>
            <div class="max-w-7xl mx-auto grid lg:grid-cols-[.85fr_1.15fr] gap-16">
                <div>
                    <p class="uppercase tracking-[0.3em] text-xs text-[#7a6f63] mb-4">{{ data_get($bookingSettings, 'eyebrow', 'Prenota') }}</p>
                    <h2 class="text-5xl md:text-6xl mb-8" style="font-family:'Cormorant Garamond',serif;">
                        {{ data_get($bookingSettings, 'heading', 'Prenota la tua esperienza') }}
                    </h2>
                    <p class="text-[#5f574d] leading-relaxed text-lg max-w-2xl">
                        {{ data_get($bookingSettings, 'body', 'Compila il modulo con le informazioni principali. Ti ricontatteremo per confermare disponibilità, dettagli e orari.') }}
                    </p>
                </div>

                <form method="POST" action="{{ route('booking.store') }}" class="bg-white/55 border border-[#d8cdbd] p-6 md:p-10">
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
                                $oldValue = old("fields.{$field->key}");
                                $inputType = $field->type === 'datetime' ? 'datetime-local' : $field->type;
                                $minimumValue = match ($field->type) {
                                    'date' => now()->addDay()->toDateString(),
                                    'datetime' => now()->addDay()->startOfDay()->format('Y-m-d\TH:i'),
                                    default => null,
                                };
                                $inputClasses = 'w-full border border-[#d8cdbd] bg-white/80 px-4 py-3 text-[#2f2a24] outline-none focus:border-[#6f6a45] transition';
                            @endphp

                            <div class="{{ $field->is_full_width || in_array($field->type, ['textarea', 'checkbox'], true) ? 'md:col-span-2' : '' }}">
                                @if ($field->type === 'checkbox')
                                    <label class="flex gap-3 text-[#5f574d] leading-relaxed">
                                        <input type="checkbox" name="{{ $fieldName }}" value="1" @checked($oldValue) class="mt-1 h-5 w-5 border-[#d8cdbd] text-[#6f6a45] focus:ring-[#6f6a45]">
                                        <span>
                                            {{ $field->label }}@if ($field->is_required)<span class="text-red-700">*</span>@endif
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
                                            @foreach ($field->optionsList() as $option)
                                                <option value="{{ $option }}" @selected($oldValue === $option)>{{ $option }}</option>
                                            @endforeach
                                        </select>
                                    @else
                                        <input id="booking-{{ $field->key }}" type="{{ $inputType }}" name="{{ $fieldName }}" value="{{ $oldValue }}" placeholder="{{ $field->placeholder }}" @if ($minimumValue) min="{{ $minimumValue }}" @endif class="{{ $inputClasses }}">
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

            const headerHeight = header.offsetHeight;
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
