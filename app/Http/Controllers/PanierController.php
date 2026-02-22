<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Panier;
use App\Models\Plat;
use Illuminate\Support\Facades\Auth;

class PanierController extends Controller
{
    // Voir mon panier
    public function index()
    {
        $panier = Panier::where('etudiant_id', Auth::id())->with('plat')->get();
        return response()->json($panier);
    }

    // Ajouter un article au panier
    public function store(Request $request)
    {
        $request->validate([
            'plat_id' => 'required|exists:plats,id',
            'quantite' => 'required|integer|min:1'
        ]);

        // Vérifier si le plat existe déjà dans le panier de l'étudiant
        $item = Panier::where('etudiant_id', Auth::id())
                      ->where('plat_id', $request->plat_id)
                      ->first();

        if ($item) {
            $item->increment('quantite', $request->quantite);
        } else {
            Panier::create([
                'etudiant_id' => Auth::id(),
                'plat_id' => $request->plat_id,
                'quantite' => $request->quantite
            ]);
        }

        return response()->json(['message' => 'Ajouté au panier']);
    }

    // Supprimer un article du panier
    public function destroy($id)
    {
        Panier::where('id', $id)->where('etudiant_id', Auth::id())->delete();
        return response()->json(['message' => 'Retiré du panier']);
    }

    // Vider le panier (après commande)
    public function vider()
    {
        Panier::where('etudiant_id', Auth::id())->delete();
        return response()->json(['message' => 'Panier vidé']);
    }

}
