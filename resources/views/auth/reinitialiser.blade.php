@extends('layouts.app')

@section('titre', 'Nouveau mot de passe')

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
    .auth-card h2 { font-size: 22px; color: var(--encre); margin-bottom: 25px; }
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
    .error-msg {
        color: var(--alerte); font-size: 13px; margin-top: 5px;
    }
</style>
@endsection

@section('contenu')
<div class="page-auth">
    <div class="auth-card">

        <h2>Choisir un nouveau mot de passe</h2>

        <form method="POST" action="{{ route('mdp.reinitialiser.store') }}">
            @csrf

            <!-- Infos venant du lien reçu par email -->
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">

            <div class="form-groupe">
                <label>Nouveau mot de passe</label>
                <input type="password" name="mot_de_passe"
                    placeholder="8 caractères minimum" required>
                @error('mot_de_passe')
                    <div class="error-msg">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-groupe">
                <label>Confirmer le mot de passe</label>
                <input type="password" name="mot_de_passe_confirmation"
                    placeholder="Retapez le mot de passe" required>
            </div>

            @error('email')
                <div class="error-msg">{{ $message }}</div>
            @enderror

            <button type="submit" class="btn-submit">Enregistrer</button>
        </form>

    </div>
</div>
@endsection
