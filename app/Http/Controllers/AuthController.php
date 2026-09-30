<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Utilisateur;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use App\Mail\ReinitialisationMotDePasseMail;

class AuthController extends Controller
{
    // ─── Afficher page inscription ───
    public function showInscription()
    {
        return view('auth.inscription');
    }

    // ─── Traiter inscription ───
    public function inscription(Request $request)
    {
        $request->validate([
            'nom'             => 'required|string|max:100',
            'prenom'          => 'required|string|max:100',
            'email'           => 'required|email|unique:utilisateurs,email',
            'mot_de_passe'    => 'required|min:8|confirmed',
            'adresse'         => 'nullable|string',
            'telephone'       => 'nullable|string|max:20',
        ]);

        $utilisateur = Utilisateur::create([
            'nom'          => $request->nom,
            'prenom'       => $request->prenom,
            'email'        => $request->email,
            'mot_de_passe' => Hash::make($request->mot_de_passe),
            'role'         => 'client',
            'adresse'      => $request->adresse,
            'telephone'    => $request->telephone,
        ]);

        // Connecter automatiquement après inscription
        session([
            'utilisateur_id'    => $utilisateur->id,
            'utilisateur_nom'   => $utilisateur->prenom,
            'utilisateur_role'  => $utilisateur->role,
        ]);

        return redirect()->route('accueil')
            ->with('success', 'Bienvenue ' . $utilisateur->prenom . ' !');
    }

    // ─── Afficher page connexion ───
    public function showConnexion()
    {
        return view('auth.connexion');
    }

    // ─── Traiter connexion ───
    public function connexion(Request $request)
    {
        $request->validate([
            'email'        => 'required|email',
            'mot_de_passe' => 'required',
        ]);

        $utilisateur = Utilisateur::where('email', $request->email)->first();

        if (!$utilisateur || !Hash::check($request->mot_de_passe, $utilisateur->mot_de_passe)) {
            return back()->with('error', 'Email ou mot de passe incorrect.');
        }

        // Nouvel identifiant de session après connexion (contre la fixation de session)
        $request->session()->regenerate();

        session([
            'utilisateur_id'   => $utilisateur->id,
            'utilisateur_nom'  => $utilisateur->prenom,
            'utilisateur_role' => $utilisateur->role,
        ]);

        // Rediriger admin vers le back-office
        if ($utilisateur->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('accueil')
            ->with('success', 'Bienvenue ' . $utilisateur->prenom . ' !');
    }

    // ─── Déconnexion ───
    public function deconnexion(Request $request)
    {
        // Détruit la session et génère un nouveau jeton CSRF
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('accueil')
            ->with('success', 'Vous êtes déconnecté.');
    }

    // ─── Afficher page mot de passe oublié ───
    public function showMotDePasseOublie()
    {
        return view('auth.mot-de-passe-oublie');
    }

    // ─── Envoyer le lien de réinitialisation ───
    public function envoyerLien(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $utilisateur = Utilisateur::where('email', $request->email)->first();

        if ($utilisateur) {
            $token = Str::random(64);

            // On garde une seule demande par email (la plus récente)
            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $utilisateur->email],
                ['token' => Hash::make($token), 'created_at' => now()]
            );

            try {
                Mail::to($utilisateur->email)->send(
                    new ReinitialisationMotDePasseMail($utilisateur, $token)
                );
            } catch (\Exception $e) {
                return back()->with('error', "L'email n'a pas pu être envoyé. Réessayez plus tard.");
            }
        }

        // Même message dans tous les cas, pour ne pas révéler quels emails sont inscrits
        return back()->with('success', 'Si cet email correspond à un compte, un lien de réinitialisation vient de vous être envoyé.');
    }

    // ─── Afficher page nouveau mot de passe ───
    public function showReinitialiser(Request $request, $token)
    {
        return view('auth.reinitialiser', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    // ─── Enregistrer le nouveau mot de passe ───
    public function reinitialiser(Request $request)
    {
        $request->validate([
            'token'        => 'required',
            'email'        => 'required|email',
            'mot_de_passe' => 'required|min:8|confirmed',
        ]);

        $demande = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        // Lien invalide ou vieux de plus de 60 minutes
        if (!$demande
            || !Hash::check($request->token, $demande->token)
            || Carbon::parse($demande->created_at)->addMinutes(60)->isPast()) {
            return redirect()->route('mdp.oublie')
                ->with('error', 'Ce lien est invalide ou a expiré. Faites une nouvelle demande.');
        }

        Utilisateur::where('email', $request->email)->update([
            'mot_de_passe' => Hash::make($request->mot_de_passe),
        ]);

        // Le lien ne peut servir qu'une fois
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('connexion')
            ->with('success', 'Mot de passe modifié ! Vous pouvez vous connecter.');
    }

    // ─── Espace client ───
    public function monCompte()
    {
        $utilisateur = Utilisateur::find(session('utilisateur_id'));
        $commandes   = $utilisateur->commandes()->orderBy('created_at', 'desc')->get();
        return view('client.mon-compte', compact('utilisateur', 'commandes'));
    }

    // ─── Modifier profil ───
    public function modifierProfil(Request $request)
    {
        $request->validate([
            'nom'       => 'required|string|max:100',
            'prenom'    => 'required|string|max:100',
            'adresse'   => 'nullable|string',
            'telephone' => 'nullable|string|max:20',
        ]);

        $utilisateur = Utilisateur::find(session('utilisateur_id'));
        $utilisateur->update([
            'nom'       => $request->nom,
            'prenom'    => $request->prenom,
            'adresse'   => $request->adresse,
            'telephone' => $request->telephone,
        ]);

        session(['utilisateur_nom' => $utilisateur->prenom]);

        return back()->with('success', 'Profil mis à jour avec succès !');
    }
}