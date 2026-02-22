<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Commandes;
use App\Models\Plat;
use App\Models\Ticket;
use App\Models\Etudiant;

class CommandeController extends Controller
{
    // On a supprimé le __construct avec le middleware ici.
    // On va le mettre dans le fichier de routes à la place.

    public function passerCommande(Request $request)
    {
        // ✅ On récupère l'ID, puis on va chercher le vrai modèle Etudiant
        $userId = Auth::id();

        if (!$userId) {
            return response()->json(['message' => 'Utilisateur non authentifié'], 401);
        }

        $etudiant = Etudiant::find($userId); // L'éditeur sait maintenant que c'est un modèle Eloquent !

        // 1️⃣ Validation
        $validator = Validator::make($request->all(), [
            'plats' => 'required|array|min:1',
            'plats.*.id' => 'required|exists:plats,id',
            'plats.*.quantite' => 'required|integer|min:1',
            'mode_paiement' => 'required|in:points,especes',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // 2️⃣ Calcul du total
        $total = 0;
        $platsSync = [];

        foreach ($request->plats as $p) {
            $platDb = Plat::find($p['id']);

            if (!$platDb || !$platDb->est_disponible) {
                return response()->json(['message' => 'Plat indisponible'], 400);
            }

            $total += $platDb->prix * $p['quantite'];

            $platsSync[$p['id']] = [
                'quantite' => $p['quantite'],
                'prix_unitaire' => $platDb->prix
            ];
        }

        // 3️⃣ Vérification du solde AVANT la transaction
        if ($request->mode_paiement === 'points' && $etudiant->solde < $total) {
            return response()->json(['message' => 'Solde insuffisant'], 400);
        }

        // 4️⃣ Transaction DB
        try {
            $commande = DB::transaction(function () use ($etudiant, $total, $request, $platsSync) {
                
                // ✅ Le soulignement de 'decrement' a disparu car $etudiant est bien reconnu
                if ($request->mode_paiement === 'points') {
                    $etudiant->decrement('solde', $total);
                }

                // Création de la commande
                $cmd = Commandes::create([
                    'etudiant_id' => $etudiant->id,
                    'date_commande' => now(),
                    'montant_total' => $total,
                    'mode_paiement' => $request->mode_paiement,
                    'statut_commande' => $request->mode_paiement === 'points' ? 'payee' : 'non_payee',
                ]);

                // Liaison des plats
                $cmd->plats()->attach($platsSync);

                // Création du ticket si espèces
                if ($request->mode_paiement === 'especes') {
                    Ticket::create([
                        'commande_id' => $cmd->id,
                        'ref_ticket' => 'TK-' . strtoupper(Str::random(6)),
                        'date_emission' => now(),
                    ]);
                }

                return $cmd;
            });

            return response()->json([
                'message' => 'Commande réussie',
                'data' => $commande
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de la commande.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}