<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rechargements extends Model
{
    // Colonnes que tu peux remplir via create()
    protected $fillable = [
        'etudiant_id',
        'caissier_id',
        'solde',       
        'commentaire'
    ];

    // Relation avec l'étudiant
    public function Etudiant()
    {
        return $this->belongsTo(User::class, 'etudiant_id');
    }

    // Relation avec le caissier
    public function Caissier()
    {
        return $this->belongsTo(User::class, 'caissier_id');
    }
}