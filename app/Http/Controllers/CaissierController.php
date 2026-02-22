<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Etudiant;
use App\Models\Commandes;
use App\Models\Rechargements;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CaissierController extends Controller
{
    /**
     * 1. Recharger le compte d'un étudiant.
     */
    public function rechargerCompte(Request $request)
    {
        $request->validate([
            'matricule' => 'required|exists:etudiants,matricule',
            'montant' => 'required|numeric|min:100' // Montant en points
        ]);

        $etudiant = Etudiant::where('matricule', $request->matricule)->first();

        DB::transaction(function () use ($etudiant, $request) {
            // Créditer l'étudiant
            $etudiant->increment('solde', $request->montant);

            // Historique du rechargement
            Rechargements::create([
                'caissier_id' => Auth::id(), // ID du caissier connecté
                'etudiant_id' => $etudiant->id,
                'montant' => $request->montant,
                'date_rechargement' => now()
            ]);
        });

        return response()->json([
            'message' => 'Compte rechargé avec succès.',
            'nouveau_solde' => $etudiant->solde
        ]);
    }

    /**
     * 2. Voir les commandes en attente de paiement espèces.
     */
    public function getCommandesEnAttente()
    {
        $commandes = Commandes::with(['etudiant', 'plats'])
            ->where('statut_commande', 'non_payee')
            ->where('mode_paiement', 'especes')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($commandes);
    }

    /**
     * 3. Valider l'encaissement d'une commande espèces.
     */
    public function encaisserCommande($commandeId)
    {
        $commande = Commandes::findOrFail($commandeId);

        if ($commande->statut_commande !== 'non_payee') {
            return response()->json(['message' => 'Cette commande a déjà été traitée.'], 400);
        }

        DB::transaction(function () use ($commande) {
            // Mettre à jour le statut (prête pour la cuisine)
            $commande->update(['statut_commande' => 'payee']);
            
            // Note: Le reçu est généré ici si nécessaire
        });

        return response()->json(['message' => 'Paiement espèces validé.']);
    }
}