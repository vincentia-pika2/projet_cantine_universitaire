<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
   protected $fillable = [
        'mode_paiement',
        'date_paiement',
        'montant',
        'commande_id'
    ];

    // Paiement lié à une commande
    public function Commandes()
    {
        return $this->belongsTo(Commandes::class);
    }

     // Paiement génère un ticket
    public function Ticket()
    {
        return $this->hasOne(Ticket::class);
    }
    
    // Paiement génère un reçu
    public function Recu()
    {
        return $this->hasOne(Recu::class);
    } 
}
