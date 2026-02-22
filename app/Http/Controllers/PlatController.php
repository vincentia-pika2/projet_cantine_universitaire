<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plat;

class PlatController extends Controller
{
    // Voir le menu (tous les plats)
    public function index() {
        return Plat::where('est_disponible', true)->get();
    }

    // Ajouter un plat (Gestionnaire)
    public function store(Request $request) {
        $data = $request->validate([
            'nom' => 'required',
            'prix' => 'required|integer',
            'description' => 'nullable',
            'image' => 'nullable'
        ]);
        return Plat::create($data);
    }

    // Désactiver un plat
    public function toggleDisponibilite($id) {
        $Plat = Plat::findOrFail($id);
        $Plat->update(['est_disponible' => !$Plat->est_disponible]);
        return $Plat;
    }

}
