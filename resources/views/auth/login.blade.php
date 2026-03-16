<x-layouts.guest>
    <style>
        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #F7F5F0;
            padding: 1.5rem;
            font-family: 'Instrument Sans', sans-serif;
             margin: -2rem -1rem;
        }
        .dark .auth-container { background: #0f172a; }

        .auth-card {
            width: 100%;
            max-width: 520px;
            background: #fff;
            border: 1px solid #E2DDD6;
            border-radius: 16px;
            padding: 2.5rem;
            box-shadow: 0 4px 24px rgba(0,0,0,0.06);
        }
        .dark .auth-card {
            background: #1E293B;
            border-color: #334155;
            box-shadow: 0 4px 24px rgba(0,0,0,0.3);
        }

        .auth-logo {
            font-family: 'Syne', sans-serif;
            font-size: 1.5rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            color: #1A1A18;
            text-decoration: none;
            display: block;
            margin-bottom: 0.5rem;
        }
        .dark .auth-logo { color: #E2E8F0; }
        .auth-logo span { color: #1E40AF; }
        .dark .auth-logo span { color: #3B82F6; }

        .auth-subtitle {
            font-size: 0.875rem;
            color: #6B6B65;
            margin-bottom: 2rem;
        }
        .dark .auth-subtitle { color: #94A3B8; }

        .auth-field { margin-bottom: 1.1rem; }

        .auth-label {
            display: block;
            font-size: 0.82rem;
            font-weight: 500;
            color: #6B6B65;
            margin-bottom: 0.4rem;
        }
        .dark .auth-label { color: #94A3B8; }

        .auth-input {
            width: 100%;
            padding: 0.65rem 0.9rem;
            font-family: 'Instrument Sans', sans-serif;
            font-size: 0.9rem;
            background: #F7F5F0;
            border: 1px solid #E2DDD6;
            border-radius: 8px;
            color: #1A1A18;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .dark .auth-input {
            background: #0f172a;
            border-color: #334155;
            color: #E2E8F0;
        }
        .auth-input:focus {
            border-color: #1E40AF;
            box-shadow: 0 0 0 3px rgba(30, 64, 175, 0.1);
        }
        .dark .auth-input:focus {
            border-color: #3B82F6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }

        .auth-remember {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 1.25rem 0;
        }
        .auth-remember label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.82rem;
            color: #6B6B65;
            cursor: pointer;
        }
        .dark .auth-remember label { color: #94A3B8; }
        .auth-remember input[type="checkbox"] {
            width: 15px; height: 15px;
            accent-color: #1E40AF;
        }

        .auth-forgot {
            font-size: 0.82rem;
            color: #1E40AF;
            text-decoration: none;
        }
        .dark .auth-forgot { color: #3B82F6; }
        .auth-forgot:hover { text-decoration: underline; }

        .auth-submit {
            width: 100%;
            padding: 0.75rem;
            font-family: 'Instrument Sans', sans-serif;
            font-size: 0.95rem;
            font-weight: 500;
            color: #fff;
            background: #1E40AF;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.2s, transform 0.15s;
        }
        .dark .auth-submit { background: #3B82F6; }
        .auth-submit:hover { background: #2563EB; transform: translateY(-1px); }
        .dark .auth-submit:hover { background: #60A5FA; }

        .auth-divider {
            height: 1px;
            background: #E2DDD6;
            margin: 1.5rem 0;
        }
        .dark .auth-divider { background: #334155; }

        .auth-footer {
            text-align: center;
            font-size: 0.85rem;
            color: #6B6B65;
        }
        .dark .auth-footer { color: #94A3B8; }
        .auth-footer a { color: #1E40AF; text-decoration: none; font-weight: 500; }
        .dark .auth-footer a { color: #3B82F6; }
        .auth-footer a:hover { text-decoration: underline; }

        .auth-back {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.82rem;
            color: #6B6B65;
            text-decoration: none;
            margin-bottom: 1.75rem;
            transition: color 0.2s;
        }
        .dark .auth-back { color: #94A3B8; }
        .auth-back:hover { color: #1E40AF; }
        .dark .auth-back:hover { color: #3B82F6; }
    </style>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=syne:700,800|instrument-sans:400,500" rel="stylesheet" />

    <div class="auth-container">
        <div class="auth-card">

            <a href="/" class="auth-back">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
                </svg>
                Retour à l'accueil
            </a>

            <a href="/" class="auth-logo">Web<span>Gestion</span></a>
            <p class="auth-subtitle">Connectez-vous à votre espace de gestion</p>

            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="auth-field">
                    <label class="auth-label" for="email">Adresse e-mail</label>
                    <input id="email" type="email" name="email"
                           value="{{ old('email') }}"
                           required autofocus autocomplete="username"
                           class="auth-input"
                           placeholder="vous@exemple.com">
                    <x-input-error :messages="$errors->get('email')" class="mt-1" />
                </div>

                <div class="auth-field">
                    <label class="auth-label" for="password">Mot de passe</label>
                    <input id="password" type="password" name="password"
                           required autocomplete="current-password"
                           class="auth-input"
                           placeholder="••••••••">
                    <x-input-error :messages="$errors->get('password')" class="mt-1" />
                </div>

                <div class="auth-remember">
                    <label>
                        <input type="checkbox" name="remember" id="remember_me">
                        Se souvenir de moi
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="auth-forgot">Mot de passe oublié ?</a>
                    @endif
                </div>

                <button type="submit" class="auth-submit">Se connecter</button>
            </form>

            <div class="auth-divider"></div>

            <p class="auth-footer">
                Pas encore de compte ?
                <a href="{{ route('register') }}">Créer un compte gratuitement</a>
            </p>

        </div>
    </div>
</x-layouts.guest>