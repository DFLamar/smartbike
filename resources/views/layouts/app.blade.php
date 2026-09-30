<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartBike — @yield('titre', 'Vélos Electriques')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;600&family=IBM+Plex+Sans:wght@400;600;700&display=swap">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @yield('styles')
</head>
<body>

<!-- ─── EN-TÊTE ─── -->
<header class="entete">
    <div class="entete-in conteneur">
        <a href="{{ route('accueil') }}" class="logo">SMARTBIKE</a>
        <nav class="menu">
            <a href="{{ route('accueil') }}" class="{{ request()->routeIs('accueil') ? 'actif' : '' }}">Accueil</a>
            <a href="{{ route('catalogue') }}" class="{{ request()->routeIs('catalogue', 'produit.show') ? 'actif' : '' }}">Catalogue</a>
            <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'actif' : '' }}">Contact</a>
            <a href="{{ route('panier') }}" class="{{ request()->routeIs('panier') ? 'actif' : '' }}">Panier</a>
        </nav>
        <div class="entete-compte">
            @if(session('utilisateur_id'))
                <a href="{{ route('mon.compte') }}" class="btn btn-contour btn-petit">
                    {{ session('utilisateur_nom') }}
                </a>
                <a href="#" onclick="event.preventDefault(); document.getElementById('form-deconnexion').submit();" class="lien-deconnexion">Déconnexion</a><form id="form-deconnexion" method="POST" action="{{ route('deconnexion') }}" style="display:none">@csrf</form>
            @else
                <a href="{{ route('connexion') }}" class="btn btn-principal btn-petit">Connexion</a>
            @endif
        </div>
    </div>
</header>

<!-- ─── MESSAGES ─── -->
@if(session('success') || session('error'))
    <div class="conteneur">
        @if(session('success'))
            <div class="message message-succes">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="message message-erreur">{{ session('error') }}</div>
        @endif
    </div>
@endif

<!-- ─── CONTENU ─── -->
@yield('contenu')

<!-- ─── PIED DE PAGE ─── -->
<footer class="pied">
    <div class="conteneur">
        <div class="pied-grille">
            <div>
                <div class="pied-logo">SMARTBIKE</div>
                <p>Votre mobilité électrique commence ici.</p>
            </div>
            <div>
                <h4>Liens utiles</h4>
                <a href="{{ route('accueil') }}">Accueil</a>
                <a href="{{ route('catalogue') }}">Catalogue</a>
                <a href="{{ route('contact') }}">Contact</a>
            </div>
            <div>
                <h4>Contact</h4>
                <a href="#">contact@smartbike.fr</a>
                <a href="#">01 23 45 67 89</a>
            </div>
        </div>
        <div class="pied-bas">
            © 2025 SmartBike — Tous droits réservés
        </div>
    </div>
</footer>

</body>
</html>
