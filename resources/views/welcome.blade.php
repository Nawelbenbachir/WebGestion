<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>WebGestion – Logiciel de facturation en ligne gratuit</title>
    <!-- <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=syne:400,600,700,800|instrument-sans:400,500" rel="stylesheet" /> -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        
        :root {
            --bg: #F7F5F0;
            --ink: #1A1A18;
            --accent: #1E40AF;
            --accent-light: #2563EB;
            --muted: #6B6B65;
            --card-bg: #FFFFFF;
            --border: #E2DDD6;
        }

       @media (prefers-color-scheme: dark) {
                :root {
                    --bg: #0f172a;
                    --ink: #E2E8F0;
                    --accent: #3B82F6;
                    --accent-light: #60A5FA;
                    --muted: #94A3B8;
                    --card-bg: #1E293B;
                    --border: #334155;
                }
            }

        body {
            font-family: 'Instrument Sans', sans-serif;
            background-color: var(--bg);
            color: var(--ink);
            min-height: 100vh;
            overflow-x: hidden;
            transition: background-color 0.3s, color 0.3s;
        }

        /* ── NAV ── */
        nav {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.25rem 2.5rem;
            background: rgba(247, 245, 240, 0.85);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            transition: background 0.3s;
        }
       

        @media (prefers-color-scheme: dark) {
            nav {
                background: rgba(15, 23, 42, 0.85);
            }
        }


        .nav-logo {
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 1.35rem;
            letter-spacing: -0.03em;
            color: var(--ink);
            text-decoration: none;
        }

        .nav-logo span { color: var(--accent); }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        /* Toggle dark mode btn */
        .toggle-dark {
            width: 36px; height: 36px;
            border-radius: 8px;
            border: 1px solid var(--border);
            background: var(--card-bg);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            transition: all 0.2s;
            color: var(--ink);
        }

        .toggle-dark:hover {
            border-color: var(--accent);
        }

        .btn-ghost {
            padding: 0.5rem 1.25rem;
            font-family: 'Instrument Sans', sans-serif;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--ink);
            text-decoration: none;
            border: 1px solid transparent;
            border-radius: 6px;
            transition: border-color 0.2s, background 0.2s;
        }

        .btn-ghost:hover {
            border-color: var(--border);
            background: var(--card-bg);
        }

        .btn-primary {
            padding: 0.5rem 1.25rem;
            font-family: 'Instrument Sans', sans-serif;
            font-size: 0.875rem;
            font-weight: 500;
            color: #fff;
            text-decoration: none;
            background: var(--accent);
            border: 1px solid var(--accent);
            border-radius: 6px;
            transition: background 0.2s, transform 0.15s;
        }

        .btn-primary:hover {
            background: var(--accent-light);
            border-color: var(--accent-light);
        }

        /* ── HERO ── */
        .hero {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 8rem 2rem 4rem;
            position: relative;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.35rem 1rem;
            background: rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(59, 130, 246, 0.3);
            border-radius: 99px;
            font-size: 0.78rem;
            font-weight: 500;
            color: var(--accent);
            margin-bottom: 1.75rem;
            animation: fadeUp 0.6s ease both;
        }

        .hero-badge::before {
            content: '';
            width: 6px; height: 6px;
            background: var(--accent);
            border-radius: 50%;
        }

        .hero h1 {
            font-family: 'Syne', sans-serif;
            font-size: clamp(2.5rem, 6vw, 5rem);
            font-weight: 800;
            line-height: 1.05;
            letter-spacing: -0.04em;
            max-width: 800px;
            margin-bottom: 1.5rem;
            animation: fadeUp 0.6s 0.1s ease both;
        }

        .hero h1 em {
            font-style: normal;
            color: var(--accent);
        }

        .hero p {
            font-size: 1.125rem;
            color: var(--muted);
            max-width: 520px;
            line-height: 1.7;
            margin-bottom: 2.5rem;
            animation: fadeUp 0.6s 0.2s ease both;
        }

        .hero-cta {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            justify-content: center;
            animation: fadeUp 0.6s 0.3s ease both;
        }

        .btn-hero {
            padding: 0.85rem 2rem;
            font-family: 'Instrument Sans', sans-serif;
            font-size: 1rem;
            font-weight: 500;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .btn-hero-primary {
            background: var(--accent);
            color: #fff;
            border: 1px solid var(--accent);
        }

        .btn-hero-primary:hover {
            background: var(--accent-light);
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(59, 130, 246, 0.3);
        }

        .btn-hero-secondary {
            background: var(--card-bg);
            color: var(--ink);
            border: 1px solid var(--border);
        }

        .btn-hero-secondary:hover {
            border-color: var(--accent);
            transform: translateY(-1px);
        }

 

        /* ── FEATURES ── */
        .features {
            padding: 5rem 2rem;
            max-width: 1100px;
            margin: 0 auto;
        }

        .section-label {
            font-family: 'Syne', sans-serif;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--accent);
            margin-bottom: 1rem;
        }

        .section-title {
            font-family: 'Syne', sans-serif;
            font-size: clamp(1.75rem, 3vw, 2.5rem);
            font-weight: 700;
            letter-spacing: -0.03em;
            margin-bottom: 3.5rem;
            max-width: 500px;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .feature-card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 2rem;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .feature-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 32px rgba(59, 130, 246, 0.1);
        }

        .feature-icon {
            width: 44px; height: 44px;
            background: rgba(59, 130, 246, 0.1);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.25rem;
            font-size: 1.25rem;
        }

        .feature-card h3 {
            font-family: 'Syne', sans-serif;
            font-size: 1.05rem;
            font-weight: 700;
            margin-bottom: 0.6rem;
            letter-spacing: -0.02em;
        }

        .feature-card p {
            font-size: 0.9rem;
            color: var(--muted);
            line-height: 1.65;
        }

        /* ── STATS ── */
        .stats {
            background: var(--card-bg);
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
            padding: 4rem 2rem;
        }

        .stats-inner {
            max-width: 1100px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            text-align: center;
        }

        .stat-number {
            font-family: 'Syne', sans-serif;
            font-size: 3rem;
            font-weight: 800;
            color: var(--accent);
            letter-spacing: -0.04em;
            line-height: 1;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 0.9rem;
            color: var(--muted);
        }

        /* ── CTA BOTTOM ── */
        .cta-section {
            padding: 6rem 2rem;
            text-align: center;
            max-width: 700px;
            margin: 0 auto;
        }

        .cta-section h2 {
            font-family: 'Syne', sans-serif;
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 800;
            letter-spacing: -0.04em;
            margin-bottom: 1.25rem;
        }

        .cta-section p {
            color: var(--muted);
            font-size: 1rem;
            margin-bottom: 2.5rem;
            line-height: 1.7;
        }

        /* ── FOOTER ── */
        footer {
            border-top: 1px solid var(--border);
            padding: 1.5rem 2.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.82rem;
            color: var(--muted);
        }
       

        ── ANIMATIONS ──
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

    {{-- NAV --}}
    <nav>
        <a href="/" class="nav-logo">Web<span>Gestion</span></a>
        <div class="nav-links">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/tableau-de-bord') }}" class="btn-ghost">Tableau de bord</a>
                @else
                    <a href="{{ route('login') }}" class="btn-ghost">Se connecter</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn-primary">Créer un compte</a>
                    @endif
                @endauth
            @endif
        </div>
    </nav>

    {{-- HERO --}}
    <section class="hero">
        
        <h1>Le logiciel de facturation<br><em>simple et gratuit</em></h1>
        <p>Créez vos devis, factures et avoirs en quelques clics. Gérez vos clients, votre stock et votre comptabilité depuis n'importe quel appareil.</p>
        <div class="hero-cta">
            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="btn-hero btn-hero-primary">Commencer gratuitement →</a>
            @endif
            <a href="{{ route('login') }}" class="btn-hero btn-hero-secondary">Se connecter</a>
        </div>
    </section>

    {{-- FEATURES --}}
    <section class="features">
        <p class="section-label">Fonctionnalités</p>
        <h2 class="section-title">Tout ce dont vous avez besoin, rien de superflu.</h2>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">🧾</div>
                <h3>Facturation complète</h3>
                <p>Devis, commandes, factures, avoirs et bons de livraison. Transformation automatique d'un document à l'autre.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"></div>
                <h3>Gestion des clients</h3>
                <p>Fiche client détaillée, historique des documents, import de fichiers GED et export CSV/PDF.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"></div>
                <h3>Suivi des stocks</h3>
                <p>Approvisionnements, retraits, inventaires automatisés et alertes sur les produits en rupture.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"></div>
                <h3>Statistiques & reporting</h3>
                <p>Chiffre d'affaires, factures payées/impayées, suivi des ventes par produit et des devis acceptés.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"></div>
                <h3>Suivi des paiements</h3>
                <p>Enregistrez vos règlements (virement, chèque, espèces) avec multi-règlements sur un même document.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"></div>
                <h3>Export comptabilité</h3>
                <p>Export des factures et avoirs au format CSV compatible avec votre logiciel de comptabilité.</p>
            </div>
        </div>
    </section>

    {{-- STATS --}}
    <section class="stats">
        <div class="stats-inner">
            <div>
                <div class="stat-number">100%</div>
                <div class="stat-label">Gratuit, sans engagement</div>
            </div>
            <div>
                <div class="stat-number">∞</div>
                <div class="stat-label">Documents illimités</div>
            </div>
            <div>
                <div class="stat-number">2026</div>
                <div class="stat-label">Prêt pour la facturation électronique</div>
            </div>
            <div>
                <div class="stat-number">Multi</div>
                <div class="stat-label">Sociétés & utilisateurs</div>
            </div>
        </div>
    </section>

    {{-- CTA BOTTOM --}}
    <section class="cta-section">
        <h2>Prêt à simplifier votre gestion ?</h2>
        <p>Rejoignez WebGestion et gérez votre activité depuis votre navigateur, sans installation, sans abonnement.</p>
        @if (Route::has('register'))
            <a href="{{ route('register') }}" class="btn-hero btn-hero-primary">Créer mon compte gratuitement →</a>
        @endif
    </section>

    {{-- FOOTER --}}
    <footer>
        <span>© {{ date('Y') }} WebGestion</span>
        <span>Logiciel de facturation en ligne gratuit</span>
    </footer>

</body>
</html>