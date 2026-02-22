<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recu extends Model
{
     protected $fillable = ['date_recu', 'paiement_id'];

    public function Paiement()
    {
        return $this->belongsTo(Paiement::class);
    }

}
