<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;


class Gestionnaire extends Authenticatable

{
  
         use HasApiTokens;

    protected $fillable = ['nom', 'prenom', 'email', 'mot_de_passe', 'telephone', 'adresse'];
    protected $hidden = ['mot_de_passe'];

    // Pas de relations directes stockées, le gestionnaire supervise tout via les contrôleurs

}
