@extends('layouts.app')

@section('titre', 'Contact')

@section('styles')
<style>
    .page-contact {
        padding: 60px 80px;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 60px;
        max-width: 1200px;
        margin: 0 auto;
    }

    /* ─── INFOS CONTACT ─── */
    .contact-infos h1 {
        font-size: 36px; color: var(--encre);
        margin-bottom: 15px;
    }
    .contact-infos p {
        font-size: 16px; color: var(--encre-douce);
        line-height: 1.7; margin-bottom: 30px;
    }
    .contact-card {
        display: flex; align-items: flex-start;
        gap: 15px; margin-bottom: 25px;
        background: var(--teinte); border-radius: 12px;
        padding: 20px;
    }
    .contact-card h3 {
        font-size: 15px; font-weight: bold;
        color: var(--encre); margin-bottom: 4px;
    }
    .contact-card p {
        font-size: 14px; color: var(--encre-douce); margin: 0;
    }

    /* ─── FORMULAIRE ─── */
    .contact-form {
        background: var(--fond); border-radius: 16px;
        padding: 35px;
        border: 1px solid var(--encre);
    }
    .contact-form h2 {
        font-size: 22px; color: var(--encre);
        margin-bottom: 25px;
    }
    .form-groupe { margin-bottom: 20px; }
    .form-groupe label {
        display: block; font-size: 14px;
        font-weight: bold; color: var(--encre);
        margin-bottom: 6px;
    }
    .form-groupe input,
    .form-groupe select,
    .form-groupe textarea {
        width: 100%; padding: 12px;
        border: 1px solid var(--trait);
        border-radius: 8px; font-size: 14px;
        outline: none; font-family: Arial, sans-serif;
        transition: border-color 0.2s;
        background: var(--fond); color: var(--encre);
    }
    .form-groupe input:focus,
    .form-groupe select:focus,
    .form-groupe textarea:focus { border-color: var(--cobalt); }
    .form-groupe textarea { height: 140px; resize: vertical; }
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
    .btn-envoyer {
        width: 100%; background: var(--cobalt); color: var(--fond);
        padding: 14px; border: none; border-radius: 8px;
        font-size: 16px; font-weight: bold; cursor: pointer;
        transition: background 0.2s;
    }
    .btn-envoyer:hover { background: var(--encre); }
    .error-msg { color: var(--alerte); font-size: 13px; margin-top: 5px; }

    /* ─── FIL D'ARIANE ─── */
    .fil-ariane { padding: 15px 80px; font-size: 14px; color: var(--encre-douce); }
    .fil-ariane a { color: var(--encre-douce); text-decoration: none; }
    .fil-ariane a:hover { color: var(--cobalt); }
</style>
@endsection

@section('contenu')

<!-- Fil d'Ariane -->
<div class="fil-ariane">
    <a href="{{ route('accueil') }}">Accueil</a> > Contact
</div>

<div class="page-contact">

    <!-- ─── INFOS ─── -->
    <div class="contact-infos">
        <h1>Contactez-nous</h1>
        <p>Vous avez une question sur nos vélos électriques, une commande en cours ou besoin d'un conseil ? Notre équipe est là pour vous aider !</p>

        <div class="contact-card">
            <div>
                <h3>Email</h3>
                <p>contact@smartbike.fr</p>
                <p>Réponse sous 24h</p>
            </div>
        </div>

        <div class="contact-card">
            <div>
                <h3>Téléphone</h3>
                <p>01 23 45 67 89</p>
                <p>Lundi - Vendredi : 9h - 18h</p>
            </div>
        </div>

        <div class="contact-card">
            <div>
                <h3>Adresse</h3>
                <p>12 rue de la Mobilité</p>
                <p>75001 Paris, France</p>
            </div>
        </div>

        <div class="contact-card">
            <div>
                <h3>Horaires</h3>
                <p>Lundi - Vendredi : 9h00 - 18h00</p>
                <p>Samedi : 10h00 - 16h00</p>
            </div>
        </div>
    </div>

    <!-- ─── FORMULAIRE ─── -->
    <div class="contact-form">
        <h2>Envoyer un message</h2>

        <form method="POST" action="{{ route('contact.envoyer') }}">
            @csrf

            <div class="form-grid">
                <div class="form-groupe">
                    <label>Nom *</label>
                    <input type="text" name="nom"
                        value="{{ old('nom') }}"
                        placeholder="Votre nom" required>
                    @error('nom')
                        <div class="error-msg">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-groupe">
                    <label>Email *</label>
                    <input type="email" name="email"
                        value="{{ old('email') }}"
                        placeholder="votre@email.fr" required>
                    @error('email')
                        <div class="error-msg">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-groupe">
                <label>Sujet *</label>
                <select name="sujet" required>
                    <option value="">Choisir un sujet</option>
                    <option value="Question produit" {{ old('sujet') == 'Question produit' ? 'selected' : '' }}>
                        Question sur un produit
                    </option>
                    <option value="Suivi commande" {{ old('sujet') == 'Suivi commande' ? 'selected' : '' }}>
                        Suivi de commande
                    </option>
                    <option value="Retour produit" {{ old('sujet') == 'Retour produit' ? 'selected' : '' }}>
                        Retour / Remboursement
                    </option>
                    <option value="Problème technique" {{ old('sujet') == 'Problème technique' ? 'selected' : '' }}>
                        Problème technique
                    </option>
                    <option value="Autre" {{ old('sujet') == 'Autre' ? 'selected' : '' }}>
                        Autre
                    </option>
                </select>
                @error('sujet')
                    <div class="error-msg">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-groupe">
                <label>Message *</label>
                <textarea name="message"
                    placeholder="Décrivez votre demande en détail..."
                    required>{{ old('message') }}</textarea>
                @error('message')
                    <div class="error-msg">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn-envoyer">
                Envoyer le message
            </button>
        </form>
    </div>

</div>

@endsection