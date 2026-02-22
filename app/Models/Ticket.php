<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
       protected $fillable = ['numero', 'date_ticket', 'paiement_id'];

    public function Paiement()
    {
        return $this->belongsTo(Paiement::class);
    }
}
