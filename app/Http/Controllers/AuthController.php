<?php

namespace App\Http\Controllers;
use App\Models\Etudiant;
use App\Models\Caissier;
use App\Models\Gestionnaire;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    // Connexion Étudiant
    public function loginEtudiant(Request $request) {
        $user = Etudiant::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Identifiants incorrects'], 401);
        }
        $token = $user->createToken('etudiant-token', ['role:etudiant'])->plainTextToken;
        return response()->json(['token' => $token, 'user' => $user]);
    }

    // Connexion Caissier
    public function loginCaissier(Request $request) {
        $user = Caissier::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Identifiants incorrects'], 401);
        }
        $token = $user->createToken('caissier-token', ['role:caissier'])->plainTextToken;
        return response()->json(['token' => $token, 'user' => $user]);
    }

    // Connexion Gestionnaire
    public function loginGestionnaire(Request $request) {
        $user = Gestionnaire::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Identifiants incorrects'], 401);
        }
        $token = $user->createToken('gestionnaire-token', ['role:gestionnaire'])->plainTextToken;
        return response()->json(['token' => $token, 'user' => $user]);
    }
}
