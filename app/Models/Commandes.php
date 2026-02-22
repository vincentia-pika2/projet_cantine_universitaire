<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commandes extends Model
{
    protected $fillable = [
        'etudiant_id', 
        'date_commande', 
        'montant_total', 
        'statut_commande', // 'en_attente', 'prete', 'livree'
        'mode_paiement'    // 'points', 'especes'
    ];

    // Relation : Appartient à un étudiant [cite: 67, 68]
    public function Etudiant()
    {
        return $this->belongsTo(Etudiant::class);
    }

    // Relation : Contient plusieurs plats (Table pivot 'commande_plat') [cite: 72]
    public function Plats()
    {
        return $this->belongsToMany(Plat::class, 'commande_plat')
                    ->withPivot('quantite', 'prix_unitaire'); // Important pour l'historique des prix
    }

    // Relation : Une commande a un paiement associé [cite: 73, 74]
    public function Paiement()
    {
        return $this->hasOne(Paiement::class);
    }

   /*  // Relation : Une commande génère un ticket (si espèces) [cite: 94, 91]
    public function ticket()
    {
        return $this->hasOne(Ticket::class);
    }

    // Relation : Une commande génère un reçu (si payé) [cite: 96, 98]
    public function recu()
    {
        return $this->hasOne(Recu::class);
    } */
}