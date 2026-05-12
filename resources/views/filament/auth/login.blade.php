<style>
    @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500&display=swap');

    .fi-simple-layout {
        background: #f3efe7;
        min-height: 100vh;
    }

    .fi-simple-main {
        width: min(100%, 407px) !important;
        max-width: 407px !important;
    }

    .lamaka-auth-card {
        background: rgba(255, 255, 255, .72);
        border: 1px solid #d8cdbd;
        color: #2f2a24;
        padding: 1.3rem 1.65rem;
    }

    .lamaka-auth-logo {
        width: 6.5rem;
        max-width: 45%;
        margin-bottom: 1.45rem;
    }

    .lamaka-auth-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: clamp(1.85rem, 4vw, 2.35rem);
        font-weight: 400;
        line-height: .95;
        margin: 0 0 .55rem;
    }

    .lamaka-auth-copy {
        color: #5f574d;
        font-size: .95rem;
        line-height: 1.35;
        margin-bottom: 1.25rem;
    }

    .lamaka-auth-message {
        border: 1px solid #d8cdbd;
        color: #4f4a35;
        font-size: .85rem;
        line-height: 1.4;
        margin-bottom: 1rem;
        padding: .75rem .9rem;
    }

    .lamaka-auth-message.error {
        background: #fff4f0;
        border-color: #d9a79b;
        color: #8a3528;
    }

    .lamaka-auth-message.success {
        background: #f3efe7;
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
        gap: .8rem;
    }

    .lamaka-auth-card .fi-sc-actions,
    .lamaka-auth-card .fi-fo-actions {
        margin-top: .85rem;
    }
</style>

<div class="lamaka-auth-card">
    <img src="/logo.png" alt="LAMAKA" class="lamaka-auth-logo">

    <h1 class="lamaka-auth-title">Accesso admin</h1>
    <p class="lamaka-auth-copy">Entra nell’area riservata LAMAKA.</p>

    @if (session('status'))
        <div class="lamaka-auth-message success">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="lamaka-auth-message error">
            {{ $errors->first() }}
        </div>
    @endif

    @if ($this->loginError)
        <div class="lamaka-auth-message error">
            {{ $this->loginError }}
        </div>
    @endif

    {{ $this->content }}
</div>
