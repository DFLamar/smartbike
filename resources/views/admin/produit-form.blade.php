<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartBike — {{ isset($produit) ? 'Modifier' : 'Ajouter' }} un produit</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; display: flex; background: #ECEEF1; }

        /* ─── SIDEBAR ─── */
        .sidebar {
            width: 240px; min-height: 100vh;
            background: #161B26; padding: 25px 0;
            position: fixed; left: 0; top: 0;
        }
        .sidebar-logo { font-size: 20px; font-weight: bold; color: #ECEEF1; padding: 0 20px 5px; }
        .sidebar-subtitle { font-size: 12px; color: #B8BFCA; padding: 0 20px 25px; }
        .sidebar-menu { list-style: none; }
        .sidebar-menu a {
            display: flex; align-items: center; gap: 10px;
            padding: 12px 20px; text-decoration: none;
            color: #B8BFCA; font-size: 14px;
        }
        .sidebar-menu a:hover { background: #4A5263; color: #ECEEF1; }
        .sidebar-menu a.active { background: #2340D9; color: #ECEEF1; }
        .sidebar-menu .deconnexion a { color: #D6362B; }

        /* ─── CONTENU ─── */
        .main-content { margin-left: 240px; padding: 40px; width: calc(100% - 240px); }
        .page-title { font-size: 28px; margin-bottom: 30px; }

        /* ─── FORMULAIRE ─── */
        .form-card {
            background: #ECEEF1; border-radius: 12px;
            padding: 35px; border: 1px solid #161B26;
            max-width: 800px;
        }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .form-grid .full { grid-column: span 2; }
        .form-groupe { display: flex; flex-direction: column; gap: 6px; margin-bottom: 5px; }
        .form-groupe label { font-size: 14px; font-weight: bold; color: #161B26; }
        .form-groupe input,
        .form-groupe select,
        .form-groupe textarea {
            padding: 12px; border: 1px solid #B8BFCA;
            border-radius: 8px; font-size: 14px; outline: none;
            font-family: Arial, sans-serif;
            background: #ECEEF1; color: #161B26;
        }
        .form-groupe input:focus,
        .form-groupe select:focus,
        .form-groupe textarea:focus { border-color: #2340D9; }
        .form-groupe textarea { height: 100px; resize: vertical; }
        .error-msg { color: #D6362B; font-size: 13px; }

        /* ─── APERÇU IMAGE ─── */
        .image-preview {
            width: 150px; height: 150px; border-radius: 8px;
            background: #D5D9DF; display: flex; align-items: center;
            justify-content: center; color: #2340D9; font-size: 13px;
            margin-top: 10px; overflow: hidden;
        }
        .image-preview img { width: 100%; height: 100%; object-fit: cover; }
        .images-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; }
        .image-slot { display: flex; flex-direction: column; gap: 8px; min-width: 0; }
        .image-slot .image-preview { width: 100%; height: auto; aspect-ratio: 1; margin-top: 0; }
        .image-slot input[type="file"] { padding: 6px; font-size: 12px; width: 100%; }
        .image-slot-titre { font-size: 13px; color: #4A5263; }
        .image-supprimer {
            display: flex; align-items: center; gap: 6px;
            font-size: 12px !important; font-weight: normal !important; color: #D6362B !important;
        }

        /* ─── BOUTONS ─── */
        .form-actions { display: flex; gap: 15px; margin-top: 25px; }
        .btn-sauvegarder {
            background: #2340D9; color: #ECEEF1; padding: 12px 30px;
            border: none; border-radius: 8px; font-size: 15px;
            font-weight: bold; cursor: pointer;
        }
        .btn-sauvegarder:hover { background: #161B26; }
        .btn-annuler {
            background: #D5D9DF; color: #4A5263; padding: 12px 30px;
            border: none; border-radius: 8px; font-size: 15px;
            text-decoration: none; font-weight: bold;
        }
        .btn-annuler:hover { background: #B8BFCA; }

        /* ─── RESPONSIVE ─── */
        @media (max-width: 900px) {
            body { flex-direction: column; }
            .sidebar {
                position: static; width: 100%; min-height: 0;
                padding: 15px 0 0;
            }
            .sidebar-subtitle { padding-bottom: 12px; }
            .sidebar-menu { display: flex; flex-wrap: wrap; }
            .sidebar-menu a { padding: 10px 16px; }
            .main-content { margin-left: 0; width: 100%; padding: 25px 20px; }
            .section-card { overflow-x: auto; }
            table { min-width: 560px; }
        }
        @media (max-width: 600px) {
            .page-title { font-size: 22px; }
            .page-header { flex-wrap: wrap; gap: 12px; }
            .section-card { padding: 18px; }
            .form-card { padding: 20px; }
            .form-grid { grid-template-columns: 1fr; }
            .form-grid .full { grid-column: auto; }
            .images-grid { grid-template-columns: 1fr 1fr; }
            .form-actions { flex-wrap: wrap; }
            .btn-sauvegarder, .btn-annuler { flex: 1; text-align: center; }
        }
    </style>
</head>
<body>

<!-- ─── SIDEBAR ─── -->
<aside class="sidebar">
    <div class="sidebar-logo">SmartBike</div>
    <div class="sidebar-subtitle">Administration</div>
    <ul class="sidebar-menu">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('admin.produits') }}" class="active">Produits</a></li>
        <li><a href="{{ route('admin.commandes') }}">Commandes</a></li>
        <li class="deconnexion"><a href="#" onclick="event.preventDefault(); document.getElementById('form-deconnexion').submit();">Déconnexion</a><form id="form-deconnexion" method="POST" action="{{ route('deconnexion') }}" style="display:none">@csrf</form></li>
    </ul>
</aside>

<!-- ─── CONTENU ─── -->
<main class="main-content">
    <h1 class="page-title">
        {{ isset($produit) ? 'Modifier le produit' : 'Ajouter un produit' }}
    </h1>

    <div class="form-card">
        <form method="POST"
              action="{{ isset($produit) ? route('admin.produits.update', $produit->id) : route('admin.produits.store') }}"
              enctype="multipart/form-data">
            @csrf

            <div class="form-grid">

                <!-- Nom -->
                <div class="form-groupe full">
                    <label>Nom du produit *</label>
                    <input type="text" name="nom"
                        value="{{ old('nom', $produit->nom ?? '') }}"
                        placeholder="Ex: CityRider Pro" required>
                    @error('nom') <div class="error-msg">{{ $message }}</div> @enderror
                </div>

                <!-- Catégorie -->
                <div class="form-groupe">
                    <label>Catégorie *</label>
                    <select name="id_categorie" required>
                        <option value="">Choisir une catégorie</option>
                        @foreach($categories as $categorie)
                        <option value="{{ $categorie->id }}"
                            {{ old('id_categorie', $produit->id_categorie ?? '') == $categorie->id ? 'selected' : '' }}>
                            {{ $categorie->libelle }}
                        </option>
                        @endforeach
                    </select>
                    @error('id_categorie') <div class="error-msg">{{ $message }}</div> @enderror
                </div>

                <!-- Prix -->
                <div class="form-groupe">
                    <label>Prix (€) *</label>
                    <input type="number" name="prix" step="0.01" min="0"
                        value="{{ old('prix', $produit->prix ?? '') }}"
                        placeholder="Ex: 1299.00" required>
                    @error('prix') <div class="error-msg">{{ $message }}</div> @enderror
                </div>

                <!-- Autonomie -->
                <div class="form-groupe">
                    <label>Autonomie (km) *</label>
                    <input type="number" name="autonomie" min="0"
                        value="{{ old('autonomie', $produit->autonomie ?? '') }}"
                        placeholder="Ex: 80" required>
                    @error('autonomie') <div class="error-msg">{{ $message }}</div> @enderror
                </div>

                <!-- Puissance -->
                <div class="form-groupe">
                    <label>Puissance (W) *</label>
                    <input type="number" name="puissance" min="0"
                        value="{{ old('puissance', $produit->puissance ?? '') }}"
                        placeholder="Ex: 250" required>
                    @error('puissance') <div class="error-msg">{{ $message }}</div> @enderror
                </div>

                <!-- Poids -->
                <div class="form-groupe">
                    <label>Poids (kg)</label>
                    <input type="number" name="poids" step="0.1" min="0"
                        value="{{ old('poids', $produit->poids ?? '') }}"
                        placeholder="Ex: 18.5">
                </div>
                
                <!-- Stock -->
                <div class="form-groupe">
                    <label>Stock *</label>
                    <input type="number" name="stock" min="0"
                        value="{{ old('stock', $produit->stock ?? '') }}"
                        placeholder="Ex: 10" required>
                    @error('stock') <div class="error-msg">{{ $message }}</div> @enderror
                </div>

                <!-- Description -->
                <div class="form-groupe full">
                    <label>Description</label>
                    <textarea name="description"
                        placeholder="Description du vélo électrique...">{{ old('description', $produit->description ?? '') }}</textarea>
                </div>

                <!-- Images (jusqu'à 4) -->
                <div class="form-groupe full">
                    <label>Images du produit</label>
                    <div class="images-grid">
                        @foreach(['image' => 'Image principale', 'image2' => 'Image 2', 'image3' => 'Image 3', 'image4' => 'Image 4'] as $champ => $libelle)
                        <div class="image-slot">
                            <span class="image-slot-titre">{{ $libelle }}</span>
                            <div class="image-preview" id="apercu-{{ $champ }}">
                                @if(isset($produit) && $produit->$champ)
                                    <img src="{{ asset('storage/' . trim($produit->$champ)) }}"
                                         alt="{{ $produit->nom }}">
                                @else
                                    Aucune image
                                @endif
                            </div>
                            <input type="file" name="{{ $champ }}" accept="image/*"
                                   onchange="apercuImage(this, 'apercu-{{ $champ }}')">
                            @if(isset($produit) && $produit->$champ)
                                <label class="image-supprimer">
                                    <input type="checkbox" name="supprimer_{{ $champ }}" value="1">
                                    Supprimer cette image
                                </label>
                            @endif
                            @error($champ) <div class="error-msg">{{ $message }}</div> @enderror
                        </div>
                        @endforeach
                    </div>
                </div>

            </div>

            <div class="form-actions">
                <button type="submit" class="btn-sauvegarder">
                    {{ isset($produit) ? 'Modifier le produit' : 'Ajouter le produit' }}
                </button>
                <a href="{{ route('admin.produits') }}" class="btn-annuler">Annuler</a>
            </div>
        </form>
    </div>
</main>

<script>
    // Aperçu de l'image choisie avant l'envoi du formulaire
    function apercuImage(input, idApercu) {
        const apercu = document.getElementById(idApercu);
        if (!input.files || !input.files[0]) return;
        const img = document.createElement('img');
        img.src = URL.createObjectURL(input.files[0]);
        apercu.innerHTML = '';
        apercu.appendChild(img);
    }
</script>

</body>
</html>