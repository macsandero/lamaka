@if ($bookingSuspension)
    <script>
        document.addEventListener('click', event => {
            const link = event.target.closest('a[href]');
            if (!link) return;

            const url = new URL(link.href, window.location.href);
            if (url.origin !== window.location.origin || url.hash !== '#prenota') return;

            event.preventDefault();
            event.stopImmediatePropagation();
            window.alert(@json($bookingSuspension->suspensionMessage()));
        }, true);
    </script>
@endif
