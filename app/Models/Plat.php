<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plat extends Model
{
    protected $fillable = ['nom', 'prix', 'description', 'image', 'disponible'];

    // Relation : Un plat peut être dans plusieurs menus [cite: 80, 89]
    public function Menus()
    {
        return $this->belongsToMany(Menu::class, 'menu_plat');
    }

    // Relation : Un plat peut être dans plusieurs commandes
    public function Commandes()
    {
        return $this->belongsToMany(Commandes::class, 'commande_plat')
                    ->withPivot('quantite', 'prix_unitaire');
    }

    // Relation : Un plat peut être dans plusieurs paniers
    public function Paniers()
    {
        return $this->belongsToMany(Panier::class, 'panier_plat')
                    ->withPivot('quantite');
    }
}