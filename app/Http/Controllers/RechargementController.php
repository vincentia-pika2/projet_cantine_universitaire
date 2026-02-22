<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rechargement;
use App\Models\rechargements;
use Illuminate\Support\Facades\Auth;

class RechargementController extends Controller
{
    // Pour l'étudiant : Voir MON historique
    public function monHistorique()
    {
        $User = Auth::User();
        
        // On récupère les rechargements liés à l'étudiant connecté
        $historique = Rechargements::where('etudiant_id', $User->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($historique);
    }

    // Pour le Gestionnaire : Voir TOUS les rechargements (Audit)
    public function index()
    {
        // On charge aussi le nom du caissier et de l'étudiant pour l'affichage
        $Rechargements = Rechargements::with(['etudiant:id,nom,prenom', 'caissier:id,nom'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($Rechargements);
    }
}