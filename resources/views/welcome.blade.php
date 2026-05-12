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

<body class="bg-[#f3efe7] text-[#2f2a24] overflow-x-hidden">

    <header id="site-header" class="fixed top-0 left-0 right-0 z-50 border-b border-[#d8cdbd]" style="background-color:#f3efe7;background-image:radial-gradient(rgba(120,98,72,.035) .7px,transparent .7px),radial-gradient(rgba(120,98,72,.025) .7px,#f3efe7 .7px);background-size:18px 18px;background-position:0 0,9px 9px;">

    <div class="max-w-7xl mx-auto px-6 md:px-12 py-4 flex items-center justify-between">

        <a href="#" class="flex items-center">
            <img src="/logo.png" alt="LAMAKA" class="w-28 md:w-36">
        </a>

        <nav class="hidden md:flex items-center gap-10 text-xs uppercase tracking-[0.22em] text-[#5f574d]">
            <a href="#esperienze" class="js-scroll hover:text-[#2f2a24] transition">Esperienze</a>
            <a href="#chi-siamo" class="js-scroll hover:text-[#2f2a24] transition">Chi siamo</a>
            <a href="#animali" class="js-scroll hover:text-[#2f2a24] transition">Animali</a>
            <a href="#contatti" class="js-scroll hover:text-[#2f2a24] transition">Contatti</a>
        </nav>

        <a href="#contatti"
           class="js-scroll hidden md:inline-block border border-[#6f6a45] text-[#4f4a35] px-5 py-3 text-xs uppercase tracking-[0.22em] hover:bg-[#6f6a45] hover:text-white transition duration-500">
            Prenota
        </a>

        <button
            id="mobile-menu-button"
            class="md:hidden flex flex-col gap-1.5"
        >
            <span class="w-6 h-[1px] bg-[#4f4a35]"></span>
            <span class="w-6 h-[1px] bg-[#4f4a35]"></span>
            <span class="w-6 h-[1px] bg-[#4f4a35]"></span>
        </button>

    </div>

    <div
        id="mobile-menu"
        class="hidden md:hidden border-t border-[#d8cdbd]"
    >
        <div class="flex flex-col px-6 py-6 gap-6 text-xs uppercase tracking-[0.22em] text-[#5f574d]">

            <a href="#esperienze" class="js-scroll mobile-link">Esperienze</a>
            <a href="#chi-siamo" class="js-scroll mobile-link">Chi siamo</a>
            <a href="#animali" class="js-scroll mobile-link">Animali</a>
            <a href="#contatti" class="js-scroll mobile-link">Contatti</a>

            <a
                href="#contatti"
                class="js-scroll mobile-link border border-[#6f6a45] text-[#4f4a35] px-5 py-3 text-center"
            >
                Prenota
            </a>

        </div>
    </div>

</header>

<script>
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');

    mobileMenuButton.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
    });

    document.querySelectorAll('.mobile-link').forEach(link => {
        link.addEventListener('click', () => {
            mobileMenu.classList.add('hidden');
        });
    });
</script>

    <main class="pt-[104px]">

        <section class="relative h-[calc(100vh-104px)] overflow-hidden bg-[#2f2a24]">
            <video autoplay muted loop playsinline class="absolute inset-0 w-full h-full object-cover scale-[1.03]">
                <source src="/videos/hero-optimized.mp4" type="video/mp4">
            </video>

            <div class="absolute inset-0 bg-black/30"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-black/55 via-black/10 to-black/10"></div>

            <div class="relative z-10 flex h-full items-end px-6 md:px-12 pb-16 md:pb-24">
                <div class="max-w-3xl">
                    <p class="text-white/75 uppercase tracking-[0.3em] text-xs mb-4">Cadore • Dolomiti</p>

                    <h1 class="text-white text-5xl md:text-7xl leading-none font-light" style="font-family:'Cormorant Garamond',serif;">
                        Passeggiate nella natura<br>
                        con lama e alpaca
                    </h1>

                    <p class="text-white/85 mt-6 text-base md:text-lg max-w-xl leading-relaxed">
                        Esperienze lente tra lago, boschi e montagne.
                        Un tempo sospeso da vivere insieme ai nostri animali.
                    </p>

                    <a href="#esperienze" class="js-scroll inline-block mt-10 bg-[#f3efe7] text-[#2f2a24] px-8 py-4 uppercase tracking-[0.25em] text-xs hover:bg-white transition duration-500">
                        Scopri le esperienze
                    </a>
                </div>
            </div>
        </section>

        <section id="esperienze" class="pt-24 pb-58 px-6 md:px-12" style="background-color:#f3efe7;background-image:radial-gradient(rgba(120,98,72,.035) .7px,transparent .7px),radial-gradient(rgba(120,98,72,.025) .7px,#f3efe7 .7px);background-size:18px 18px;background-position:0 0,9px 9px;">
            <div class="max-w-6xl mx-auto">
                <p class="uppercase tracking-[0.3em] text-xs text-[#7a6f63] mb-4">Esperienze</p>

                <h2 class="text-5xl md:text-6xl mb-16" style="font-family:'Cormorant Garamond',serif;">
                    Natura, lentezza e relazione
                </h2>

                <div class="grid md:grid-cols-2 gap-10">
                    <div class="bg-white/45 p-8">
                        <img src="/images/esperienze/Foto%20diAlpaca%20e%20lama%20completa.jpeg" alt="Lama e alpaca LAMAKA" class="w-full h-[400px] object-cover mb-6">
                        <h3 class="text-3xl mb-4" style="font-family:'Cormorant Garamond',serif;">Primo incontro</h3>
                        <p class="text-[#5f574d] leading-relaxed">Una passeggiata semplice e immersiva per conoscere lama e alpaca, camminando tra lago, sentieri e natura.</p>
                    </div>

                    <div class="bg-white/45 p-8">
                        <img src="/images/esperienze/due%20lama%20al%20pascolo.jpeg" alt="Due lama al pascolo" class="w-full h-[400px] object-cover mb-6">
                        <h3 class="text-3xl mb-4" style="font-family:'Cormorant Garamond',serif;">Passeggiata al tramonto</h3>
                        <p class="text-[#5f574d] leading-relaxed">Un’esperienza lenta e romantica tra le montagne del Cadore, accompagnati dal ritmo calmo degli animali.</p>
                    </div>
                </div>
            </div>
        </section>

        <section id="chi-siamo" class="py-32 px-6 md:px-12" style="background-color:#f3efe7;background-image:radial-gradient(rgba(120,98,72,.035) .7px,transparent .7px),radial-gradient(rgba(120,98,72,.025) .7px,#f3efe7 .7px);background-size:18px 18px;background-position:0 0,9px 9px;">
            <div class="max-w-7xl mx-auto grid md:grid-cols-2 gap-16 items-center">
                <div>
                    <p class="uppercase tracking-[0.3em] text-xs text-[#7a6f63] mb-4">Chi siamo</p>

                    <h2 class="text-5xl md:text-6xl mb-8" style="font-family:'Cormorant Garamond',serif;">
                        Un tempo lento da condividere
                    </h2>

                    <div class="space-y-6 text-[#5f574d] leading-relaxed text-lg">
                        <p>LAMAKA nasce dal desiderio di creare esperienze autentiche nella natura, accompagnati dal passo lento e silenzioso di lama e alpaca.</p>
                        <p>Tra boschi, montagne e paesaggi del Cadore, ogni passeggiata diventa un’occasione per rallentare, respirare e ritrovare una connessione semplice con gli animali e con il territorio.</p>
                        <p>Non una semplice attività turistica, ma un’esperienza da vivere insieme.</p>
                    </div>
                </div>

                <div>
                    <img src="/images/about/chi-siamo.jpeg" alt="Chi siamo - LAMAKA" class="w-full h-[700px] object-cover">
                </div>
            </div>
        </section>

        <section id="animali" class="py-32 px-6 md:px-12" style="background-color:#efe7da;background-image:radial-gradient(rgba(120,98,72,.03) .7px,transparent .7px),radial-gradient(rgba(120,98,72,.02) .7px,#efe7da .7px);background-size:18px 18px;background-position:0 0,9px 9px;">
            <div class="max-w-7xl mx-auto">
                <p class="uppercase tracking-[0.3em] text-xs text-[#7a6f63] mb-4">Gli animali</p>

                <h2 class="text-5xl md:text-6xl mb-20" style="font-family:'Cormorant Garamond',serif;">
                    Cinque personalità,<br>
                    un solo passo lento
                </h2>

                <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-10">
                    <div class="bg-white/50 p-6">
                        <img src="/images/animali/Athos.jpeg" alt="Athos" class="w-full h-[500px] object-cover mb-6">
                        <h3 class="text-3xl mb-3" style="font-family:'Cormorant Garamond',serif;">Athos</h3>
                        <p class="text-[#5f574d] leading-relaxed">Curioso e sempre attento a ciò che succede attorno a lui. Ama osservare le persone e avvicinarsi con delicatezza.</p>
                    </div>

                    <div class="bg-white/50 p-6">
                        <img src="/images/animali/Kairos.jpeg" alt="Kairos" class="w-full h-[500px] object-cover mb-6">
                        <h3 class="text-3xl mb-3" style="font-family:'Cormorant Garamond',serif;">Kairos</h3>
                        <p class="text-[#5f574d] leading-relaxed">Dolce e tranquillo, trasmette calma già dal primo incontro. È perfetto per chi cerca un momento di relax autentico.</p>
                    </div>

                    <div class="bg-white/50 p-6">
                        <img src="/images/animali/Skiantos-2.jpeg" alt="Skiantos" class="w-full h-[500px] object-cover mb-6">
                        <h3 class="text-3xl mb-3" style="font-family:'Cormorant Garamond',serif;">Skiantos</h3>
                        <p class="text-[#5f574d] leading-relaxed">Il leader del gruppo. Sicuro di sé, curioso e sempre pronto ad aprire la strada durante le passeggiate.</p>
                    </div>

                    <div class="bg-white/50 p-6">
                        <img src="/images/animali/Gulliver.jpeg" alt="Gulliver" class="w-full h-[500px] object-cover mb-6">
                        <h3 class="text-3xl mb-3" style="font-family:'Cormorant Garamond',serif;">Gulliver</h3>
                        <p class="text-[#5f574d] leading-relaxed">Elegante e riflessivo, ama i ritmi lenti e le passeggiate silenziose immerso nella natura.</p>
                    </div>

                    <div class="bg-white/50 p-6">
                        <img src="/images/animali/Francis.jpeg" alt="Francis" class="w-full h-[500px] object-cover mb-6">
                        <h3 class="text-3xl mb-3" style="font-family:'Cormorant Garamond',serif;">Francis</h3>
                        <p class="text-[#5f574d] leading-relaxed">Affettuoso e socievole, crea subito empatia con adulti e bambini grazie al suo carattere gentile.</p>
                    </div>
                </div>
            </div>
        </section>

        <section id="contatti"></section>

    </main>

    <script>
        document.querySelectorAll('.js-scroll').forEach((link) => {
            link.addEventListener('click', function (event) {
                const targetId = this.getAttribute('href');
                if (!targetId || !targetId.startsWith('#')) return;

                const target = document.querySelector(targetId);
                const header = document.querySelector('#site-header');
                if (!target || !header) return;

                event.preventDefault();

                const headerHeight = header.offsetHeight;
                const targetTop = target.getBoundingClientRect().top + window.scrollY;

                window.scrollTo({
                    top: targetTop - headerHeight,
                    behavior: 'smooth'
                });
            });
        });
    </script>

</body>
</html>