<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Panier extends Model
{
     protected $fillable = ['etudiant_id'];

  
    // Le panier appartient à un étudiant
    public function Etudiant()
    {
        return $this->belongsTo(Etudiant::class, 'etudiant_id');
    }

    // Le panier contient plusieurs plats (table pivot panier_plat)
    public function Plats()
    {
        return $this->belongsToMany(Plat::class, 'panier_plat', 'panier_id', 'plat_id');
    }

    // Le panier peut devenir une commande
    public function commandes()
    {
        return $this->hasOne(Commandes::class, 'panier_id');
    }

}
