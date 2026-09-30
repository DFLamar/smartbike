<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CatalogueController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\PanierController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ContactController;

// ─── PAGE ACCUEIL ───
Route::get('/', [CatalogueController::class, 'accueil'])->name('accueil');

// ─── CATALOGUE ───
Route::get('/catalogue', [CatalogueController::class, 'index'])->name('catalogue');
Route::get('/produit/{id}', [ProduitController::class, 'show'])->name('produit.show');

// ─── CONTACT ───
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'envoyer'])->name('contact.envoyer');

// ─── AUTHENTIFICATION ───
Route::get('/inscription', [AuthController::class, 'showInscription'])->name('inscription');
Route::post('/inscription', [AuthController::class, 'inscription'])->name('inscription.store');
Route::get('/connexion', [AuthController::class, 'showConnexion'])->name('connexion');
Route::post('/connexion', [AuthController::class, 'connexion'])->name('connexion.store')->middleware('throttle:5,1');
Route::post('/deconnexion', [AuthController::class, 'deconnexion'])->name('deconnexion');
Route::get('/mot-de-passe-oublie', [AuthController::class, 'showMotDePasseOublie'])->name('mdp.oublie');
Route::post('/mot-de-passe-oublie', [AuthController::class, 'envoyerLien'])->name('mdp.envoyer');
Route::get('/reinitialiser-mot-de-passe/{token}', [AuthController::class, 'showReinitialiser'])->name('mdp.reinitialiser');
Route::post('/reinitialiser-mot-de-passe', [AuthController::class, 'reinitialiser'])->name('mdp.reinitialiser.store');

// ─── PANIER ───
Route::middleware('auth.custom')->group(function () {
    Route::get('/panier', [PanierController::class, 'index'])->name('panier');
    Route::post('/panier/ajouter', [PanierController::class, 'ajouter'])->name('panier.ajouter');
    Route::post('/panier/modifier', [PanierController::class, 'modifier'])->name('panier.modifier');
    Route::post('/panier/supprimer', [PanierController::class, 'supprimer'])->name('panier.supprimer');
});

// ─── COMMANDE ───
Route::middleware('auth.custom')->group(function () {
    Route::get('/commande', [CommandeController::class, 'index'])->name('commande');
    Route::post('/commande/store', [CommandeController::class, 'store'])->name('commande.store');
    Route::get('/commande/confirmation/{id}', [CommandeController::class, 'confirmation'])->name('commande.confirmation');
    Route::get('/commande/succes', [CommandeController::class, 'succes'])->name('commande.succes');
    Route::get('/commande/annuler/{id}', [CommandeController::class, 'annuler'])->name('commande.annuler');
});

// ─── ESPACE CLIENT ───
Route::middleware('auth.custom')->group(function () {
    Route::get('/mon-compte', [AuthController::class, 'monCompte'])->name('mon.compte');
    Route::post('/mon-compte/modifier', [AuthController::class, 'modifierProfil'])->name('mon.compte.modifier');
});

// ─── ADMIN ───
Route::prefix('admin')->middleware('auth.admin')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/produits', [AdminController::class, 'produits'])->name('admin.produits');
    Route::get('/produits/ajouter', [AdminController::class, 'ajouterProduit'])->name('admin.produits.ajouter');
    Route::post('/produits/ajouter', [AdminController::class, 'storeProduit'])->name('admin.produits.store');
    Route::get('/produits/modifier/{id}', [AdminController::class, 'modifierProduit'])->name('admin.produits.modifier');
    Route::post('/produits/modifier/{id}', [AdminController::class, 'updateProduit'])->name('admin.produits.update');
    Route::delete('/produits/supprimer/{id}', [AdminController::class, 'supprimerProduit'])->name('admin.produits.supprimer');
    Route::get('/commandes', [AdminController::class, 'commandes'])->name('admin.commandes');
    Route::get('/commandes/{id}', [AdminController::class, 'detailCommande'])->name('admin.commandes.detail');
    Route::post('/commandes/{id}/statut', [AdminController::class, 'updateStatut'])->name('admin.commandes.statut');
});