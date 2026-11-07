<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - NU Rancamaya</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #F5F4EC; color: #25351F; }
        h1, h2, h3 { font-family: 'Plus Jakarta Sans', sans-serif; }
        .field input {
            width: 100%; border: 1px solid #E5E2D6; border-radius: 12px;
            padding: 11px 14px; font-size: 14px; color: #25351F; background: #fff;
            transition: border-color .15s ease, box-shadow .15s ease;
        }
        .field input:focus { outline: none; border-color: #2F6B2F; box-shadow: 0 0 0 3px rgba(47,107,47,.15); }
        .field label { display: block; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 14px; font-weight: 600; color: #183D22; margin-bottom: 6px; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-sm">
        <a href="{{ route('home') }}" class="flex items-center justify-center gap-2.5 mb-6">
            <span class="w-11 h-11 rounded-2xl flex items-center justify-center" style="background:#2F6B2F">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l2.4 7.6H22l-6 4.6 2.3 7.3L12 17l-6.3 4.5L8 14.2 2 9.6h7.6z"/></svg>
            </span>
            <span>
                <span class="block font-extrabold text-xl leading-tight" style="color:#183D22">NU Rancamaya</span>
                <span class="block text-xs" style="color:#7D8575">Portal Digital Nahdlatul Ulama</span>
            </span>
        </a>

        <div class="bg-white rounded-[28px] shadow-sm border border-black/5 p-8">
            <h2 class="text-2xl font-extrabold text-center" style="color:#183D22">Login Admin</h2>
            <p class="text-sm text-center mt-1.5" style="color:#7D8575">Masuk untuk mengelola konten NU Rancamaya</p>

            @if($errors->any())
                <p class="rounded-2xl px-4 py-3 mt-5 text-sm font-medium" style="background:#FDE8E8; color:#991B1B; border:1px solid rgba(185,28,28,.18)">
                    {{ $errors->first() }}
                </p>
            @endif

            <form method="POST" action="{{ route('login.post') }}" class="mt-6">
                @csrf
                <div class="field">
                    <label for="username">Email atau Username</label>
                    <input id="username" type="text" name="username" value="{{ old('username') }}" required autofocus placeholder="admin@gmail.com">
                </div>
                <div class="field mt-4">
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" required placeholder="••••••••">
                </div>
                <button type="submit"
                        class="w-full mt-6 py-3 rounded-full font-bold text-white transition hover:opacity-90 active:scale-95"
                        style="background:#2F6B2F">
                    Masuk
                </button>
            </form>
        </div>

        <p class="text-center text-sm mt-5">
            <a href="{{ route('home') }}" class="font-semibold" style="color:#2F6B2F">&larr; Kembali ke website</a>
        </p>
    </div>
</body>
</html>
