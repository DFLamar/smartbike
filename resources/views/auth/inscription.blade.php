@extends('layouts.app')

@section('titre', 'Inscription')

@section('styles')
<style>
    .page-auth {
        min-height: 60vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 60px 20px;
        background: var(--fond);
    }
    .auth-card {
        background: var(--fond);
        border-radius: 12px;
        padding: 40px;
        width: 100%;
        max-width: 500px;
        border: 1px solid var(--encre);
    }
    .auth-tabs {
        display: flex;
        margin-bottom: 30px;
        border-bottom: 2px solid var(--trait);
    }
    .auth-tabs a {
        padding: 10px 30px;
        text-decoration: none;
        font-size: 16px;
        color: var(--encre-douce);
        font-weight: bold;
    }
    .auth-tabs a.active {
        color: var(--cobalt);
        border-bottom: 2px solid var(--cobalt);
        margin-bottom: -2px;
    }
    .form-groupe { margin-bottom: 20px; }
    .form-groupe label {
        display: block; font-size: 14px;
        font-weight: bold; margin-bottom: 6px; color: var(--encre);
    }
    .form-groupe input {
        width: 100%; padding: 12px;
        border: 1px solid var(--trait); border-radius: 8px;
        font-size: 14px; outline: none;
        background: var(--fond); color: var(--encre);
    }
    .form-groupe input:focus { border-color: var(--cobalt); }
    .btn-submit {
        width: 100%; background: var(--cobalt); color: var(--fond);
        padding: 14px; border: none; border-radius: 8px;
        font-size: 16px; font-weight: bold; cursor: pointer;
        margin-top: 10px;
    }
    .btn-submit:hover { background: var(--encre); }
    .lien-bas {
        text-align: center; margin-top: 15px;
        font-size: 14px; color: var(--encre-douce);
    }
    .lien-bas a { color: var(--cobalt); text-decoration: none; }
    .error-msg { color: var(--alerte); font-size: 13px; margin-top: 5px; }
</style>
@endsection

@section('contenu')
<div class="page-auth">
    <div class="auth-card">

        <!-- Onglets -->
        <div class="auth-tabs">
            <a href="{{ route('connexion') }}">Connexion</a>
            <a href="{{ route('inscription') }}" class="active">Inscription</a>
        </div>

        <form method="POST" action="{{ route('inscription.store') }}">
            @csrf

            <div class="form-groupe">
                <label>Prénom</label>
                <input type="text" name="prenom"
                    value="{{ old('prenom') }}"
                    placeholder="Votre prénom" required>
                @error('prenom')
                    <div class="error-msg">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-groupe">
                <label>Nom</label>
                <input type="text" name="nom"
                    value="{{ old('nom') }}"
                    placeholder="Votre nom" required>
                @error('nom')
                    <div class="error-msg">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-groupe">
                <label>Email</label>
                <input type="email" name="email"
                    value="{{ old('email') }}"
                    placeholder="votre@email.fr" required>
                @error('email')
                    <div class="error-msg">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-groupe">
                <label>Mot de passe</label>
                <input type="password" name="mot_de_passe"
                    placeholder="Minimum 8 caractères" required>
                @error('mot_de_passe')
                    <div class="error-msg">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-groupe">
                <label>Confirmer le mot de passe</label>
                <input type="password" name="mot_de_passe_confirmation"
                    placeholder="Répétez votre mot de passe" required>
            </div>

            <div class="form-groupe">
                <label>Adresse (optionnel)</label>
                <input type="text" name="adresse"
                    value="{{ old('adresse') }}"
                    placeholder="Votre adresse">
            </div>

            <div class="form-groupe">
                <label>Téléphone (optionnel)</label>
                <input type="text" name="telephone"
                    value="{{ old('telephone') }}"
                    placeholder="Ex: 0612345678">
            </div>

            <button type="submit" class="btn-submit">Créer mon compte</button>
        </form>

        <div class="lien-bas">
            Déjà un compte ?
            <a href="{{ route('connexion') }}">Se connecter</a>
        </div>

    </div>
</div>
@endsection