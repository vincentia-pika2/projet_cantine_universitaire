<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PlatController;
use App\Http\Controllers\EtudiantController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\PanierController;
use App\Http\Controllers\CaissierController;
use App\Http\Controllers\StatistiqueController;
use App\Http\Controllers\RechargementController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// === 1. AUTHENTIFICATION (Public) ===
Route::post('/login/etudiant', [AuthController::class, 'loginEtudiant']);
Route::post('/login/caissier', [AuthController::class, 'loginCaissier']);
Route::post('/login/gestionnaire', [AuthController::class, 'loginGestionnaire']);

// === 2. PUBLIC (Lecture seule) ===
Route::get('/menu', [PlatController::class, 'index']);

// === 3. ZONE PROTEGÉE ===
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    // --- RÔLE : ÉTUDIANT ---
    Route::middleware('ability:etudiant')->group(function () {
        Route::get('/mon-profil', [EtudiantController::class, 'profil']);
        Route::get('/mes-commandes', [EtudiantController::class, 'historique']);
        Route::post('/passer-commande', [CommandeController::class, 'passerCommande']);

        Route::get('/mes-rechargements', [RechargementController::class, 'monHistorique']);

        Route::get('/panier', [PanierController::class, 'index']);
        Route::post('/panier', [PanierController::class, 'store']);
        Route::delete('/panier/{id}', [PanierController::class, 'destroy']);
    });

    // --- RÔLE : CAISSIER ---
    Route::middleware('ability:caissier')->group(function () {
        Route::post('/recharger-compte', [CaissierController::class, 'rechargerCompte']);
        Route::get('/commandes-en-attente', [CaissierController::class, 'getCommandesEnAttente']);
        Route::post('/valider-paiement/{id}', [CaissierController::class, 'encaisserCommande']);
    });

    // --- RÔLE : GESTIONNAIRE ---
    Route::middleware('ability:gestionnaire')->group(function () {
        Route::post('/plats', [PlatController::class, 'store']);
        Route::put('/plats/{id}', [PlatController::class, 'update']);
        Route::delete('/plats/{id}', [PlatController::class, 'destroy']);

        Route::get('/statistiques', [StatistiqueController::class, 'dashbord']);

        Route::get('/tous-rechargements', [RechargementController::class, 'index']);
        Route::get('/toutes-commandes', [CommandeController::class, 'index']);
    });

});
