<style>
    @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500&display=swap');

    .fi-simple-layout {
        background: #f3efe7;
        min-height: 100vh;
    }

    .fi-simple-main {
        width: min(100%, 360px) !important;
        max-width: 360px !important;
    }

    .lamaka-auth-card {
        background: rgba(255, 255, 255, .72);
        border: 1px solid #d8cdbd;
        color: #2f2a24;
        padding: 1.65rem;
    }

    .lamaka-auth-logo {
        width: 6.5rem;
        max-width: 45%;
        margin-bottom: 2rem;
    }

    .lamaka-auth-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: clamp(1.85rem, 4vw, 2.35rem);
        font-weight: 400;
        line-height: .95;
        margin: 0 0 .75rem;
    }

    .lamaka-auth-copy {
        color: #5f574d;
        font-size: .95rem;
        line-height: 1.35;
        margin-bottom: 1.75rem;
    }

    .lamaka-auth-card .fi-fo-field-wrp-label span,
    .lamaka-auth-card label {
        color: #7a6f63 !important;
        font-size: .66rem !important;
        font-weight: 500 !important;
        letter-spacing: .24em !important;
        text-transform: uppercase !important;
    }

    .lamaka-auth-card .fi-input-wrp,
    .lamaka-auth-card input {
        border-radius: 0 !important;
    }

    .lamaka-auth-card .fi-input-wrp {
        border-color: #d8cdbd !important;
        box-shadow: none !important;
        min-height: 2.65rem;
    }

    .lamaka-auth-card .fi-input {
        font-size: .9rem !important;
        min-height: 2.65rem;
    }

    .lamaka-auth-card .fi-btn {
        background: #6f6a45 !important;
        border-radius: 0 !important;
        box-shadow: none !important;
        color: #fff !important;
        min-height: 3rem;
        text-transform: uppercase;
        letter-spacing: .24em;
        font-weight: 500;
    }

    .lamaka-auth-card .fi-btn:hover {
        background: #4f4a35 !important;
    }

    .lamaka-auth-card .fi-fo-component-ctn {
        gap: 1rem;
    }

    .lamaka-auth-card .fi-sc-actions,
    .lamaka-auth-card .fi-fo-actions {
        margin-top: 1.1rem;
    }
</style>

<div class="lamaka-auth-card">
    <img src="/logo.png" alt="LAMAKA" class="lamaka-auth-logo">

    <h1 class="lamaka-auth-title">Accesso admin</h1>
    <p class="lamaka-auth-copy">Entra nell’area riservata LAMAKA.</p>

    {{ $this->content }}
</div>
