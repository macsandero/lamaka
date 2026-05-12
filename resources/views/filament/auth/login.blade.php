<style>
    @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500&display=swap');

    .fi-simple-layout {
        background: #f3efe7;
        min-height: 100vh;
    }

    .fi-simple-main {
        width: min(100%, 760px) !important;
        max-width: 760px !important;
    }

    .lamaka-auth-card {
        background: rgba(255, 255, 255, .72);
        border: 1px solid #d8cdbd;
        color: #2f2a24;
        padding: clamp(2.5rem, 6vw, 4.5rem);
    }

    .lamaka-auth-logo {
        width: 12rem;
        max-width: 54%;
        margin-bottom: clamp(4rem, 8vw, 7rem);
    }

    .lamaka-auth-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: clamp(3.25rem, 8vw, 4.8rem);
        font-weight: 400;
        line-height: .95;
        margin: 0 0 1.8rem;
    }

    .lamaka-auth-copy {
        color: #5f574d;
        font-size: clamp(1.2rem, 3vw, 1.75rem);
        line-height: 1.35;
        margin-bottom: 4rem;
    }

    .lamaka-auth-card .fi-fo-field-wrp-label span,
    .lamaka-auth-card label {
        color: #7a6f63 !important;
        font-size: 1rem !important;
        font-weight: 500 !important;
        letter-spacing: .28em !important;
        text-transform: uppercase !important;
    }

    .lamaka-auth-card .fi-input-wrp,
    .lamaka-auth-card input {
        border-radius: 0 !important;
    }

    .lamaka-auth-card .fi-input-wrp {
        border-color: #d8cdbd !important;
        box-shadow: none !important;
        min-height: 4.25rem;
    }

    .lamaka-auth-card .fi-input {
        font-size: 1.15rem !important;
        min-height: 4.25rem;
    }

    .lamaka-auth-card .fi-btn {
        background: #6f6a45 !important;
        border-radius: 0 !important;
        box-shadow: none !important;
        color: #fff !important;
        min-height: 5rem;
        text-transform: uppercase;
        letter-spacing: .28em;
        font-weight: 500;
    }

    .lamaka-auth-card .fi-btn:hover {
        background: #4f4a35 !important;
    }

    .lamaka-auth-card .fi-fo-component-ctn {
        gap: 2rem;
    }

    .lamaka-auth-card .fi-sc-actions,
    .lamaka-auth-card .fi-fo-actions {
        margin-top: 2.2rem;
    }
</style>

<div class="lamaka-auth-card">
    <img src="/logo.png" alt="LAMAKA" class="lamaka-auth-logo">

    <h1 class="lamaka-auth-title">Accesso admin</h1>
    <p class="lamaka-auth-copy">Entra nell’area riservata LAMAKA.</p>

    {{ $this->content }}
</div>
