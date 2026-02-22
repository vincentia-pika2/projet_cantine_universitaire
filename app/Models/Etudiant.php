<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Etudiant extends Authenticatable
{  
  
    use HasApiTokens, Notifiable;

    protected $primaryKey = 'matricule';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'matricule', 'nom', 'prenom', 'email',
        'mot_de_passe', 'solde', 'filiere', 'user_id'

    ];
    protected $hidden = [
        'mot_de_passe'
    ];
    public function User() {
        return $this->belongsTo(User::class);

        }
   
    // Un étudiant peut passer plusieurs commandes
    public function Commandes()
    {
        return $this->hasMany(Commandes::class, 'etudiant_id');
    }

    // Un étudiant possède un panier
    public function Panier()
    {
        return $this->hasOne(Panier::class, 'etudiant_id');
    }

     // Un étudiant peut effectuer plusieurs paiements
    public function Paiements()
    {
        return $this->hasMany(Paiement::class, 'etudiant_id');
    }

    //un etudiant peut avoir plusieur rechargement(rechager son compte)

    public function Rechargementsrecu()
    {
        return $this->hasMany(Rechargements::class, 'etudiant_id');
    }

    // Un étudiant peut avoir plusieurs tickets/reçus
    public function Tickets()
    {
        return $this->hasMany(Ticket::class, 'etudiant_id');
    } 

}
