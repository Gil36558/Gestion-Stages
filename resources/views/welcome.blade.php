<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Plateforme de Stages : Connectez-vous à des opportunités de stages pour étudiants et entreprises. Simplifiez la gestion de vos stages dès aujourd'hui.">
    <meta name="keywords" content="stages, gestion de stages, étudiants, entreprises, opportunités professionnelles">
    <title>StageConnect - Accueil</title>

    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="Plateforme de Stages - Accueil">
    <meta property="og:description" content="Connectez étudiants et entreprises sur la plateforme de stages la plus intuitive du marché.">
    <meta property="og:image" content="https://images.unsplash.com/photo-1521737604893-d14cc237f11d?auto=format&fit=crop&w=1200&q=80">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">

    <!-- AOS CSS -->
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <!-- Tailwind CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

        :root {
            --primary-blue: #2563eb;
            --primary-dark: #1e40af;
            --primary-light: #3b82f6;
            --secondary-blue: #eff6ff;
            --accent-color: #f59e0b;
            --text-primary: #1f2937;
            --text-secondary: #6b7280;
            --background: #ffffff;
            --background-alt: #f8fafc;
            --border-color: #e5e7eb;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--background);
            color: var(--text-primary);
            line-height: 1.6;
            scroll-behavior: smooth;
        }

        /* Navbar */
        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border-color);
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .navbar.scrolled {
            background: rgba(255, 255, 255, 0.98);
            box-shadow: var(--shadow-md);
        }

        .nav-container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .nav-logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .nav-logo h1 {
            font-weight: 800;
            font-size: 1.5rem;
            background: linear-gradient(135deg, var(--primary-blue), var(--primary-light));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .logo-icon {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, var(--primary-blue), var(--primary-light));
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
        }

        .nav-menu {
            list-style: none;
            display: flex;
            gap: 2.5rem;
            margin: 0;
            padding: 0;
        }

        .nav-menu li a {
            text-decoration: none;
            color: var(--text-primary);
            font-weight: 500;
            font-size: 0.95rem;
            padding: 0.5rem 0;
            position: relative;
            transition: color 0.3s ease;
        }

        .nav-menu li a:hover {
            color: var(--primary-blue);
        }

        .nav-menu li a::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--primary-blue);
            transition: width 0.3s ease;
        }

        .nav-menu li a:hover::after {
            width: 100%;
        }

        .nav-actions {
            display: flex;
            gap: 1.5rem;
            align-items: center;
        }

        .nav-actions .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 0.9rem;
            border: 2px solid transparent;
        }

        .nav-actions .btn-outline {
            color: var(--primary-blue);
            border-color: var(--primary-blue);
        }

        .nav-actions .btn-outline:hover {
            background: var(--primary-blue);
            color: white;
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .nav-actions .btn-primary {
            background: var(--primary-blue);
            color: white;
        }

        .nav-actions .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        /* Menu Burger */
        .burger-menu {
            display: none;
            flex-direction: column;
            cursor: pointer;
            gap: 4px;
        }

        .burger-menu span {
            width: 25px;
            height: 3px;
            background: var(--primary-blue);
            border-radius: 2px;
            transition: all 0.3s ease;
        }

        .burger-menu.active span:nth-child(1) {
            transform: rotate(45deg) translate(5px, 5px);
        }

        .burger-menu.active span:nth-child(2) {
            opacity: 0;
        }

        .burger-menu.active span:nth-child(3) {
            transform: rotate(-45deg) translate(7px, -6px);
        }

        /* Hero Section */
        .hero {
            padding: 8rem 0 6rem;
            background: linear-gradient(135deg, var(--secondary-blue) 0%, #ffffff 100%);
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 50%;
            height: 100%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="2" fill="%23dbeafe" opacity="0.3"/><circle cx="20" cy="20" r="1" fill="%23dbeafe" opacity="0.2"/><circle cx="80" cy="30" r="1.5" fill="%23dbeafe" opacity="0.4"/></svg>');
            opacity: 0.1;
        }

        .hero-content {
            position: relative;
            z-index: 1;
        }

        .hero h1 {
            font-size: 3.5rem;
            font-weight: 800;
            color: var(--text-primary);
            line-height: 1.2;
            margin-bottom: 1.5rem;
        }

        .hero .subtitle {
            font-size: 1.25rem;
            color: var(--text-secondary);
            margin-bottom: 2rem;
            font-weight: 400;
        }

        .hero-buttons {
            display: flex;
            gap: 1.5rem;
            flex-wrap: wrap;
        }

        .hero-buttons .btn {
            padding: 1rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .hero-buttons .btn-primary {
            background: var(--primary-blue);
            color: white;
            border: 2px solid var(--primary-blue);
        }

        .hero-buttons .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-3px);
            box-shadow: var(--shadow-xl);
        }

        .hero-buttons .btn-outline {
            color: var(--primary-blue);
            border: 2px solid var(--primary-blue);
            background: white;
        }

        .hero-buttons .btn-outline:hover {
            background: var(--primary-blue);
            color: white;
            transform: translateY(-3px);
            box-shadow: var(--shadow-xl);
        }

        .hero-image {
            position: relative;
        }

        .hero-image img {
            border-radius: 20px;
            box-shadow: var(--shadow-xl);
            width: 100%;
            height: auto;
            max-height: 400px;
            object-fit: cover;
        }

        .hero-image::before {
            content: '';
            position: absolute;
            top: -20px;
            left: -20px;
            right: 20px;
            bottom: 20px;
            background: linear-gradient(135deg, var(--primary-blue), var(--primary-light));
            border-radius: 20px;
            z-index: -1;
            opacity: 0.1;
        }

        /* User Dashboard Section (affiché quand connecté) */
        .user-dashboard {
            padding: 4rem 0;
            background: var(--background-alt);
            text-align: center;
        }

        .user-dashboard h2 {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 1.5rem;
        }

        .user-dashboard .btn {
            padding: 1rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 1rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin: 0.5rem;
        }

        .user-dashboard .btn-primary {
            background: var(--primary-blue);
            color: white;
        }

        .user-dashboard .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-3px);
            box-shadow: var(--shadow-xl);
        }

        .user-dashboard .btn-outline {
            color: var(--primary-blue);
            border: 2px solid var(--primary-blue);
            background: white;
        }

        .user-dashboard .btn-outline:hover {
            background: var(--primary-blue);
            color: white;
            transform: translateY(-3px);
            box-shadow: var(--shadow-xl);
        }

        /* Features Section */
        .features {
            padding: 6rem 0;
            background: var(--background);
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--text-primary);
            text-align: center;
            margin-bottom: 1rem;
        }

        .section-subtitle {
            font-size: 1.1rem;
            color: var(--text-secondary);
            text-align: center;
            margin-bottom: 4rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2.5rem;
            margin-top: 4rem;
        }

        .feature {
            background: white;
            padding: 2.5rem;
            border-radius: 20px;
            text-align: center;
            box-shadow: var(--shadow-md);
            transition: all 0.3s ease;
            border: 1px solid var(--border-color);
            position: relative;
            overflow: hidden;
        }

        .feature::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-blue), var(--primary-light));
        }

        .feature:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-xl);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 1.5rem;
            background: linear-gradient(135deg, var(--primary-blue), var(--primary-light));
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
        }

        .feature h3 {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 1rem;
        }

        .feature p {
            color: var(--text-secondary);
            font-size: 1rem;
            line-height: 1.6;
        }

        /* Stats Section */
        .stats {
            padding: 4rem 0;
            background: var(--background-alt);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2.5rem;
            text-align: center;
        }

        .stat-item {
            padding: 1.5rem;
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 800;
            color: var(--primary-blue);
            display: block;
        }

        .stat-label {
            color: var(--text-secondary);
            font-size: 1rem;
            margin-top: 0.5rem;
        }

        /* Roles Section */
        .roles {
            padding: 6rem 0;
            background: var(--background);
        }

        .roles-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 3.5rem;
            margin-top: 4rem;
        }

        .role-card {
            background: white;
            padding: 3rem;
            border-radius: 20px;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }

        .role-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-xl);
        }

        .role-card h3 {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .role-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary-blue), var(--primary-light));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
        }

        .role-card ul {
            list-style: none;
            padding: 0;
        }

        .role-card li {
            color: var(--text-secondary);
            font-size: 1rem;
            padding: 0.75rem 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .role-card li::before {
            content: '✓';
            color: var(--primary-blue);
            font-weight: bold;
            font-size: 1.2rem;
        }

        /* Footer */
        footer {
            background: var(--text-primary);
            color: white;
            padding: 4rem 0 2rem;
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 3.5rem;
            margin-bottom: 3rem;
        }

        .footer-section h3 {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
        }

        .footer-section p,
        .footer-section a {
            color: #d1d5db;
            text-decoration: none;
            font-size: 0.95rem;
            line-height: 1.6;
        }

        .footer-section a:hover {
            color: var(--primary-light);
        }

        .footer-section ul {
            list-style: none;
            padding: 0;
        }

        .footer-section li {
            margin-bottom: 0.5rem;
        }

        .footer-bottom {
            border-top: 1px solid #374151;
            padding-top: 2rem;
            text-align: center;
        }

        .footer-bottom p {
            color: #9ca3af;
            font-size: 0.9rem;
        }

        /* Back to top */
        .back-to-top {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            width: 50px;
            height: 50px;
            background: var(--primary-blue);
            color: white;
            border: none;
            border-radius: 50%;
            font-size: 1.2rem;
            cursor: pointer;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .back-to-top.show {
            opacity: 1;
            visibility: visible;
        }

        .back-to-top:hover {
            background: var(--primary-dark);
            transform: translateY(-3px);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .nav-menu {
                display: none;
                position: fixed;
                top: 70px;
                left: 0;
                width: 100%;
                background: white;
                padding: 2rem;
                box-shadow: var(--shadow-lg);
                flex-direction: column;
                gap: 1.5rem;
            }

            .nav-menu.active {
                display: flex;
            }

            .burger-menu {
                display: flex;
            }

            .nav-actions {
                display: none;
            }

            .hero h1 {
                font-size: 2.5rem;
            }

            .hero .subtitle {
                font-size: 1.1rem;
            }

            .hero-buttons {
                flex-direction: column;
                align-items: stretch;
                gap: 1rem;
            }

            .section-title {
                font-size: 2rem;
            }

            .features-grid,
            .roles-grid {
                grid-template-columns: 1fr;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .user-dashboard .btn {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .nav-container {
                padding: 1rem;
            }

            .hero {
                padding: 6rem 0 4rem;
            }

            .hero h1 {
                font-size: 2rem;
            }

            .features,
            .roles {
                padding: 4rem 0;
            }

            .feature,
            .role-card {
                padding: 2rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <header class="navbar" id="navbar">
        <nav class="nav-container">
            <div class="nav-logo">
                <div class="logo-icon">
                    <i class="fas fa-graduation-cap" aria-hidden="true"></i>
                </div>
                <h1>StageConnect</h1>
            </div>
            <ul class="nav-menu" id="nav-menu">
                <li><a href="#hero" aria-label="Aller à la section Accueil">Accueil</a></li>
                <li><a href="#features" aria-label="Aller à la section Fonctionnalités">Fonctionnalités</a></li>
                <li><a href="#roles" aria-label="Aller à la section Rôles">Rôles</a></li>
                <li><a href="#stats" aria-label="Aller à la section Statistiques">Statistiques</a></li>
                <li>
                    <a href="{{ route('entreprise.index') }}" aria-label="Découvrir les entreprises" class="text-blue-600 hover:text-blue-800 font-semibold">
                        🔍 Entreprises
                    </a>
                </li>
            </ul>
            <div class="burger-menu" id="burger-menu">
                <span></span>
                <span></span>
                <span></span>
            </div>
            <div class="nav-actions">
                @guest
                    <a href="{{ route('login') }}" class="btn btn-outline" aria-label="Se connecter">Connexion</a>
                    <a href="{{ route('register') }}" class="btn btn-primary" aria-label="S'inscrire">Inscription</a>
                @else
                    @if(Auth::user()->role === 'etudiant')
                        <a href="{{ route('etudiant.dashboard') }}" class="btn btn-primary" aria-label="Accéder à l'espace étudiant">Mon espace</a>
                    @elseif(Auth::user()->role === 'entreprise')
                        <a href="{{ route('entreprise.dashboard') }}" class="btn btn-primary" aria-label="Accéder à l'espace entreprise">Espace entreprise</a>
                    @endif
                    <a href="{{ route('profile.edit') }}" class="btn btn-outline" aria-label="Modifier le profil">Profil</a>
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-outline" aria-label="Se déconnecter">Déconnexion</button>
                    </form>
                @endguest
            </div>
        </nav>
    </header>

    <main>
        <section class="hero" id="hero">
            <div class="max-w-7xl mx-auto px-4">
                <div class="grid md:grid-cols-2 gap-12 items-center">
                    <div class="hero-content" data-aos="fade-up" data-aos-duration="800">
                        <h1>Votre avenir professionnel commence ici</h1>
                        <p class="subtitle">Connectez étudiants et entreprises sur la plateforme de stages la plus intuitive du marché</p>
                        @guest
                            <div class="hero-buttons">
                                <a href="{{ route('register') }}?role=etudiant" class="btn btn-primary" aria-label="S'inscrire comme étudiant">
                                    <i class="fas fa-user-graduate"></i> Je suis étudiant
                                </a>
                                <a href="{{ route('register') }}?role=entreprise" class="btn btn-outline" aria-label="S'inscrire comme entreprise">
                                    <i class="fas fa-building"></i> Je suis une entreprise
                                </a>
                            </div>
                        @else
                            <div class="user-dashboard">
                                <h2>Bienvenue, {{ Auth::user()->name }} !</h2>
                                <div class="flex flex-wrap justify-center gap-4">
                                    @if(Auth::user()->role === 'etudiant')
                                        <a href="{{ route('etudiant.dashboard') }}" class="btn btn-primary" aria-label="Accéder au tableau de bord étudiant">
                                            <i class="fas fa-tachometer-alt"></i> Mon tableau de bord
                                        </a>
                                    @elseif(Auth::user()->role === 'entreprise')
                                        <a href="{{ route('entreprise.dashboard') }}" class="btn btn-primary" aria-label="Accéder au tableau de bord entreprise">
                                            <i class="fas fa-tachometer-alt"></i> Mon tableau de bord
                                        </a>
                                    @endif
                                    <a href="{{ route('profile.edit') }}" class="btn btn-outline" aria-label="Modifier le profil">
                                        <i class="fas fa-user-edit"></i> Gérer mon profil
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-outline" aria-label="Se déconnecter">
                                            <i class="fas fa-sign-out-alt"></i> Déconnexion
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endguest
                    </div>
                    <div class="hero-image" data-aos="fade-left" data-aos-delay="200">
                        <img src="https://images.unsplash.com/photo-1521737604893-d14cc237f11d?auto=format&fit=crop&w=800&q=80" alt="Équipe professionnelle travaillant ensemble" loading="lazy" width="800" height="400">
                    </div>
                </div>
            </div>
        </section>

        <section class="stats" id="stats">
            <div class="max-w-7xl mx-auto px-4">
                <div class="stats-grid" data-aos="fade-up">
                    <div class="stat-item">
                        <span class="stat-number">2,500+</span>
                        <span class="stat-label">Étudiants inscrits</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">450+</span>
                        <span class="stat-label">Entreprises partenaires</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">1,200+</span>
                        <span class="stat-label">Stages réalisés</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">95%</span>
                        <span class="stat-label">Taux de satisfaction</span>
                    </div>
                </div>
            </div>
        </section>

        <section class="features" id="features">
            <div class="max-w-7xl mx-auto px-4">
                <h2 class="section-title" data-aos="fade-up">Pourquoi choisir StageConnect ?</h2>
                <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">
                    Une solution complète pour simplifier la recherche de stages et optimiser le recrutement
                </p>
                <div class="features-grid">
                    <div class="feature" data-aos="fade-up" data-aos-delay="100">
                        <div class="feature-icon">
                            <i class="fas fa-search"></i>
                        </div>
                        <h3>Recherche intelligente</h3>
                        <p>Algorithme avancé pour matcher les profils étudiants avec les offres d'entreprises selon les compétences et préférences</p>
                    </div>
                    <div class="feature" data-aos="fade-up" data-aos-delay="200">
                        <div class="feature-icon">
                            <i class="fas fa-rocket"></i>
                        </div>
                        <h3>Candidature express</h3>
                        <p>Postulez en quelques clics grâce à votre profil pré-rempli et suivez vos candidatures en temps réel</p>
                    </div>
                    <div class="feature" data-aos="fade-up" data-aos-delay="300">
                        <div class="feature-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3>Gestion simplifiée</h3>
                        <p>Interface intuitive pour gérer offres, candidatures et communications entre étudiants et entreprises</p>
                    </div>
                    <div class="feature" data-aos="fade-up" data-aos-delay="400">
                        <div class="feature-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h3>Suivi avancé</h3>
                        <p>Tableaux de bord détaillés avec statistiques et analytics pour optimiser vos processus de recrutement</p>
                    </div>
                    <div class="feature" data-aos="fade-up" data-aos-delay="500">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h3>Sécurité garantie</h3>
                        <p>Données protégées et vérification des entreprises pour assurer un environnement sûr et professionnel</p>
                    </div>
                    <div class="feature" data-aos="fade-up" data-aos-delay="600">
                        <div class="feature-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <h3>Mobile-first</h3>
                        <p>Plateforme responsive et application mobile pour gérer vos stages où que vous soyez</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="roles" id="roles">
            <div class="max-w-7xl mx-auto px-4">
                <h2 class="section-title" data-aos="fade-up">Des solutions adaptées à chaque profil</h2>
                <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">
                    Que vous soyez étudiant ou entreprise, profitez d'outils spécialisés pour vos besoins
                </p>
                <div class="roles-grid">
                    <div class="role-card" data-aos="fade-up" data-aos-delay="200">
                        <h3>
                            <div class="role-icon">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                            Espace Étudiant
                        </h3>
                        <ul>
                            <li>Profil personnalisé avec CV intégré</li>
                            <li>Recommandations de stages personnalisées</li>
                            <li>Candidature en un clic</li>
                            <li>Suivi des candidatures en temps réel</li>
                            <li>Journal de stage numérique</li>
                            <li>Notifications instantanées</li>
                            <li>Réseau professionnel étudiant</li>
                        </ul>
                    </div>
                    <div class="role-card" data-aos="fade-up" data-aos-delay="400">
                        <h3>
                            <div class="role-icon">
                                <i class="fas fa-building"></i>
                            </div>
                            Espace Entreprise
                        </h3>
                        <ul>
                            <li>Publication d'offres simplifiée</li>
                            <li>Gestion centralisée des candidatures</li>
                            <li>Filtres avancés pour la sélection</li>
                            <li>Communication directe avec les candidats</li>
                            <li>Suivi des stagiaires intégrés</li>
                            <li>Statistiques et reporting</li>
                            <li>Support dédié aux entreprises</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <div class="max-w-7xl mx-auto px-4">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>StageConnect</h3>
                    <p>La plateforme de référence pour connecter étudiants et entreprises autour d'opportunités de stages enrichissantes et formatrices.</p>
                </div>
                <div class="footer-section">
                    <h3>Navigation</h3>
                    <ul>
                        <li><a href="#hero">Accueil</a></li>
                        <li><a href="#features">Fonctionnalités</a></li>
                        <li><a href="#roles">Rôles</a></li>
                        <li><a href="#stats">Statistiques</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Services</h3>
                    <ul>
                        <li><a href="{{ route('register') }}?role=etudiant">Espace étudiant</a></li>
                        <li><a href="{{ route('register') }}?role=entreprise">Espace entreprise</a></li>
                        <li><a href="#">Support</a></li>
                        <li><a href="#">Blog</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Contact</h3>
                    <p>Email: <a href="mailto:contact@stageconnect.com">contact@plateformestages.com</a></p>
                    <p>Téléphone: +229 97 00 00 00</p>
                    <p>Adresse: Abomey-Calavi, Atlantique, Bénin</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>© 2025 StageConnect. Tous droits réservés. | <a href="#">Mentions légales</a> | <a href="#">Politique de confidentialité</a></p>
            </div>
        </div>
    </footer>

    <button class="back-to-top" id="back-to-top" aria-label="Retour en haut">
        <i class="fas fa-chevron-up"></i>
    </button>

    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>
        // Initialize AOS
        AOS.init({
            once: true,
            offset: 100,
            duration: 800,
            easing: 'ease-out-cubic',
        });

        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            const backToTop = document.getElementById('back-to-top');
            
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
            
            if (window.scrollY > 300) {
                backToTop.classList.add('show');
            } else {
                backToTop.classList.remove('show');
            }
        });

        // Burger menu toggle
        document.addEventListener('DOMContentLoaded', function() {
            const burger = document.getElementById('burger-menu');
            const navMenu = document.getElementById('nav-menu');
            const navActions = document.querySelector('.nav-actions');
            
            burger.addEventListener('click', function() {
                navMenu.classList.toggle('active');
                burger.classList.toggle('active');
                navActions.style.display = navMenu.classList.contains('active') ? 'none' : 'flex';
            });
            
            const navLinks = document.querySelectorAll('.nav-menu a');
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    navMenu.classList.remove('active');
                    burger.classList.remove('active');
                    navActions.style.display = 'flex';
                });
            });
        });

        // Back to top functionality
        document.getElementById('back-to-top').addEventListener('click', function(e) {
            e.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    const offsetTop = target.offsetTop - 80;
                    window.scrollTo({
                        top: offsetTop,
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Animation des stats
        function animateStats() {
            const statNumbers = document.querySelectorAll('.stat-number');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const target = entry.target;
                        const finalValue = target.textContent;
                        const isPercentage = finalValue.includes('%');
                        const numericValue = parseInt(finalValue.replace(/[^\d]/g, ''));
                        
                        let current = 0;
                        const increment = numericValue / 50;
                        const timer = setInterval(() => {
                            current += increment;
                            if (current >= numericValue) {
                                current = numericValue;
                                clearInterval(timer);
                            }
                            target.textContent = Math.floor(current).toLocaleString() + (isPercentage ? '%' : '+');
                        }, 30);
                        
                        observer.unobserve(target);
                    }
                });
            });
            
            statNumbers.forEach(stat => observer.observe(stat));
        }

        animateStats();

        // Loading des images
        const images = document.querySelectorAll('img');
        images.forEach(img => {
            img.addEventListener('load', function() {
                this.style.opacity = '1';
            });
            img.style.opacity = '0';
            img.style.transition = 'opacity 0.3s ease';
        });

        // Hover effects pour feature cards
        const featureCards = document.querySelectorAll('.feature');
        featureCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-10px) scale(1.02)';
            });
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });

        // Ripple effect pour boutons
        const buttons = document.querySelectorAll('.btn');
        buttons.forEach(button => {
            button.addEventListener('click', function(e) {
                const ripple = document.createElement('span');
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;
                
                ripple.style.width = ripple.style.height = size + 'px';
                ripple.style.left = x + 'px';
                ripple.style.top = y + 'px';
                ripple.classList.add('ripple');
                
                this.appendChild(ripple);
                
                setTimeout(() => {
                    ripple.remove();
                }, 600);
            });
        });

        const style = document.createElement('style');
        style.textContent = `
            .btn {
                position: relative;
                overflow: hidden;
            }
            .ripple {
                position: absolute;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.6);
                transform: scale(0);
                animation: ripple-animation 0.6s linear;
                pointer-events: none;
            }
            @keyframes ripple-animation {
                to { transform: scale(4); opacity: 0; }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>