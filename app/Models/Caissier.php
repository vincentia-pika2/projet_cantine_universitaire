<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Caissier extends Authenticatable
{
    use HasApiTokens;

    protected $fillable = ['nom', 'prenom', 'email', 'telephone', 'adresse', 'mot_de_passe'];
    protected $hidden = ['mot_de_passe'];

    // Relation : Le caissier a effectué plusieurs rechargements
    public function Rechargementseffectuer()
    {
        return $this->hasMany(rechargements::class, 'cassier_id');
    }
     // le caissier a encaisser plusieur tickets
    public function encaisserticket()
    {
        return $this->hasMany(Ticket::class, 'cassier_id');
    }

    // Relation : Le caissier a encaissé plusieurs paiements (via les commandes ou paiements directs)
    // Optionnel, utile pour les logs

}
