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
        @media (prefers-color-scheme: dark) {
            .auth-container { background: #0f172a; }
        }

        .auth-card {
            width: 100%;
            max-width: 520px;
            background: #fff;
            border: 1px solid #E2DDD6;
            border-radius: 16px;
            padding: 2.5rem;
            box-shadow: 0 4px 24px rgba(0,0,0,0.06);
        }
        @media (prefers-color-scheme: dark) {
            .auth-card { background: #1E293B; border-color: #334155; box-shadow: 0 4px 24px rgba(0,0,0,0.3); }
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
        .auth-logo span { color: #1E40AF; }
        @media (prefers-color-scheme: dark) {
            .auth-logo { color: #E2E8F0; }
            .auth-logo span { color: #3B82F6; }
        }

        .auth-subtitle {
            font-size: 0.875rem;
            color: #6B6B65;
            margin-bottom: 2rem;
        }
        @media (prefers-color-scheme: dark) {
            .auth-subtitle { color: #94A3B8; }
        }

        .auth-field { margin-bottom: 1.1rem; }

        .auth-label {
            display: block;
            font-size: 0.82rem;
            font-weight: 500;
            color: #6B6B65;
            margin-bottom: 0.4rem;
        }
        @media (prefers-color-scheme: dark) {
            .auth-label { color: #94A3B8; }
        }

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
        .auth-input:focus {
            border-color: #1E40AF;
            box-shadow: 0 0 0 3px rgba(30, 64, 175, 0.1);
        }
        @media (prefers-color-scheme: dark) {
            .auth-input { background: #0f172a; border-color: #334155; color: #E2E8F0; }
            .auth-input:focus { border-color: #3B82F6; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15); }
        }

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
            margin-top: 1.25rem;
        }
        .auth-submit:hover { background: #2563EB; transform: translateY(-1px); }
        @media (prefers-color-scheme: dark) {
            .auth-submit { background: #3B82F6; }
            .auth-submit:hover { background: #60A5FA; }
        }

        .auth-divider {
            height: 1px;
            background: #E2DDD6;
            margin: 1.5rem 0;
        }
        @media (prefers-color-scheme: dark) {
            .auth-divider { background: #334155; }
        }

        .auth-footer {
            text-align: center;
            font-size: 0.85rem;
            color: #6B6B65;
        }
        @media (prefers-color-scheme: dark) {
            .auth-footer { color: #94A3B8; }
        }
        .auth-footer a { color: #1E40AF; text-decoration: none; font-weight: 500; }
        @media (prefers-color-scheme: dark) {
            .auth-footer a { color: #3B82F6; }
        }
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
        @media (prefers-color-scheme: dark) {
            .auth-back { color: #94A3B8; }
        }
        .auth-back:hover { color: #1E40AF; }
        @media (prefers-color-scheme: dark) {
            .auth-back:hover { color: #3B82F6; }
        }
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
            <p class="auth-subtitle">Créez votre espace de gestion gratuitement</p>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="auth-field">
                    <label class="auth-label" for="name">Nom complet</label>
                    <input id="name" type="text" name="name"
                           value="{{ old('name') }}"
                           required autofocus autocomplete="name"
                           class="auth-input"
                           placeholder="Jean Dupont">
                    <x-input-error :messages="$errors->get('name')" class="mt-1" />
                </div>

                <div class="auth-field">
                    <label class="auth-label" for="email">Adresse e-mail</label>
                    <input id="email" type="email" name="email"
                           value="{{ old('email') }}"
                           required autocomplete="username"
                           class="auth-input"
                           placeholder="vous@exemple.com">
                    <x-input-error :messages="$errors->get('email')" class="mt-1" />
                </div>

                <div class="auth-field">
                    <label class="auth-label" for="password">Mot de passe</label>
                    <input id="password" type="password" name="password"
                           required autocomplete="new-password"
                           class="auth-input"
                           placeholder="••••••••">
                    <x-input-error :messages="$errors->get('password')" class="mt-1" />
                </div>

                <div class="auth-field">
                    <label class="auth-label" for="password_confirmation">Confirmer le mot de passe</label>
                    <input id="password_confirmation" type="password" name="password_confirmation"
                           required autocomplete="new-password"
                           class="auth-input"
                           placeholder="••••••••">
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
                </div>

                <button type="submit" class="auth-submit">Créer mon compte</button>
            </form>

            <div class="auth-divider"></div>

            <p class="auth-footer">
                Déjà inscrit ?
                <a href="{{ route('login') }}">Se connecter</a>
            </p>

        </div>
    </div>
</x-layouts.guest>