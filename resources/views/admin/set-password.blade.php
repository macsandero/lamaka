<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Imposta password - LAMAKA</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f3efe7] text-[#2f2a24] flex items-center justify-center px-6">
    <main class="w-full max-w-md bg-white/70 border border-[#d8cdbd] p-8">
        <img src="/logo.png" alt="LAMAKA" class="w-32 mb-8">

        <h1 class="text-3xl mb-3" style="font-family:'Cormorant Garamond',serif;">Imposta la password</h1>
        <p class="text-[#5f574d] leading-relaxed mb-8">
            Primo accesso admin per {{ $email }}.
        </p>

        @if ($errors->any())
            <div class="mb-6 border border-red-200 bg-red-50 text-red-700 px-4 py-3 text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.password-setup.update', $token) }}" class="space-y-5">
            @csrf

            <div>
                <label for="password" class="block text-xs uppercase tracking-[0.2em] text-[#7a6f63] mb-2">Password</label>
                <input id="password" name="password" type="password" required minlength="10" class="w-full border border-[#d8cdbd] bg-white px-4 py-3 outline-none focus:border-[#6f6a45]">
            </div>

            <div>
                <label for="password_confirmation" class="block text-xs uppercase tracking-[0.2em] text-[#7a6f63] mb-2">Conferma password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required minlength="10" class="w-full border border-[#d8cdbd] bg-white px-4 py-3 outline-none focus:border-[#6f6a45]">
            </div>

            <button type="submit" class="w-full bg-[#6f6a45] text-white px-6 py-4 uppercase tracking-[0.2em] text-xs hover:bg-[#4f4a35] transition">
                Salva password
            </button>
        </form>
    </main>
</body>
</html>
