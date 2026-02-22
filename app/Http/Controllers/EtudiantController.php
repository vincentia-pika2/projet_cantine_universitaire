<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Etudiant;

class EtudiantController extends Controller
{
    /**
     * 1. Voir le profil de l'étudiant connecté (et son solde).
     */
    public function profil()
    {
        // Retourne l'étudiant avec ses données
        return response()->json(Auth::user());
    }

    /**
     * 2. Voir l'historique personnel des commandes.
     */
    public function historique()
    {
        $etudiant = Auth::user();
        /** @var \App\Models\Etudiant $etudiant */
        // Récupère les commandes avec les plats associés
        $Commandes = $etudiant->Commandes()
            ->with('plats')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($Commandes);
    }
}