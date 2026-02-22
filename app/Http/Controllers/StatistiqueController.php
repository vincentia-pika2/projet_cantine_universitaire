<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commande;
use App\Models\Commandes;
use App\Models\Etudiant;
use App\Models\Plat;
use Illuminate\Support\Facades\DB;

class StatistiqueController extends Controller
{
    public function dashboard()
    {
        // 1. Chiffre d'affaires total (Commandes payées uniquement)
        $chiffreAffaires = Commandes::whereIn('statut_commande', ['payee', 'en_preparation', 'prete', 'livree'])
            ->sum('montant_total');

        // 2. Nombre total de commandes aujourd'hui
        $commandesAujourdhui = Commandes::whereDate('created_at', today())->count();

        // 3. Nombre d'étudiants inscrits
        $totalEtudiants = Etudiant::count();

        // 4. Plats les plus populaires (Top 5)
        $topPlats = DB::table('commande_plat')
            ->join('plats', 'commande_plat.plat_id', '=', 'plats.id')
            ->select('plats.nom', DB::raw('SUM(commande_plat.quantite) as total_vendus'))
            ->groupBy('plats.id', 'plats.nom')
            ->orderByDesc('total_vendus')
            ->limit(5)
            ->get();

        return response()->json([
            'chiffre_affaires' => $chiffreAffaires,
            'commandes_jour' => $commandesAujourdhui,
            'total_etudiants' => $totalEtudiants,
            'plats_populaires' => $topPlats
        ]);
    }

}
