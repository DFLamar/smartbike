@extends('layouts.app')

@section('titre', 'Mot de passe oublié')

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
    .auth-card h2 { font-size: 22px; color: var(--encre); margin-bottom: 10px; }
    .auth-intro { font-size: 14px; color: var(--encre-douce); margin-bottom: 25px; line-height: 1.5; }
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
    .error-msg {
        color: var(--alerte); font-size: 13px; margin-top: 5px;
    }
</style>
@endsection

@section('contenu')
<div class="page-auth">
    <div class="auth-card">

        <h2>Mot de passe oublié</h2>
        <p class="auth-intro">
            Entrez l'adresse email de votre compte. Nous vous enverrons un lien
            pour choisir un nouveau mot de passe.
        </p>

        <form method="POST" action="{{ route('mdp.envoyer') }}">
            @csrf

            <div class="form-groupe">
                <label>Email</label>
                <input type="email" name="email"
                    value="{{ old('email') }}"
                    placeholder="votre@email.fr" required>
                @error('email')
                    <div class="error-msg">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn-submit">Envoyer le lien</button>
        </form>

        <div class="lien-bas">
            <a href="{{ route('connexion') }}">Retour à la connexion</a>
        </div>

    </div>
</div>
@endsection
