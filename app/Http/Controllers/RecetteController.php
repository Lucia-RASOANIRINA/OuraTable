<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recette;
use App\Models\Like;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RecetteController extends Controller
{
    /**
     * 1. Page d'accueil publique (sans forcément être connecté)
     */
    public function index()
    {
        try {
            $recettes = Recette::with(['ingredients', 'user', 'likes'])
                ->orderBy('created_at', 'desc')
                ->get();
            return view('home', compact('recettes'));
        } catch (\Exception $e) {
            Log::error('Erreur index: ' . $e->getMessage());
            return view('home', ['recettes' => collect()])->with('error', 'Erreur de chargement des recettes');
        }
    }

    /**
     * 2. Espace utilisateur (UserHome) - avec les likes
     */
    public function userIndex()
    {
        try {
            // Récupérer les recettes avec les relations
            $recettes = Recette::with(['ingredients', 'likes', 'user'])
                ->orderBy('created_at', 'desc')
                ->get();

            return view('page.UserHome', compact('recettes'));
        } catch (\Exception $e) {
            Log::error('Erreur userIndex: ' . $e->getMessage());
            return back()->with('error', 'Erreur de chargement des recettes');
        }
    }

    /**
     * 3. Gérer le Like (J'adore)
     */
    public function like($id)
    {
        try {
            $recette = Recette::findOrFail($id);
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous devez être connecté pour liker'
                ], 401);
            }

            // Vérifier si l'utilisateur a déjà liké
            $existingLike = $recette->likes()->where('user_id', $user->id)->first();

            if ($existingLike) {
                // Supprimer le like
                $existingLike->delete();
                $liked = false;
                $message = 'Like retiré';
            } else {
                // Ajouter le like
                $recette->likes()->create([
                    'user_id' => $user->id,
                    'recette_id' => $recette->id
                ]);
                $liked = true;
                $message = 'Like ajouté';
            }

            // Compter les likes
            $likesCount = $recette->likes()->count();

            return response()->json([
                'success' => true,
                'liked' => $liked,
                'likes_count' => $likesCount,
                'message' => $message
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Recette non trouvée'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Erreur like: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * 4. Récupérer une recette pour l'édition
     */
    public function show($id)
    {
        try {
            $recette = Recette::with(['ingredients', 'user', 'likes'])
                ->findOrFail($id);
            
            return response()->json($recette);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Recette non trouvée'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Erreur show: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * 5. Récupérer toutes les recettes (API)
     */
    public function getAll()
    {
        try {
            $recettes = Recette::with(['ingredients', 'user', 'likes'])
                ->orderBy('created_at', 'desc')
                ->get();
            
            return response()->json([
                'success' => true,
                'recettes' => $recettes
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur getAll: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * 6. Vérifier si l'utilisateur a liké une recette
     */
    public function checkLike($id)
    {
        try {
            $recette = Recette::findOrFail($id);
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Utilisateur non connecté'
                ], 401);
            }

            $isLiked = $recette->likes()->where('user_id', $user->id)->exists();
            $likesCount = $recette->likes()->count();

            return response()->json([
                'success' => true,
                'is_liked' => $isLiked,
                'likes_count' => $likesCount
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }

     public function getDetails($id)
    {
        try {
            $recette = Recette::with(['ingredients', 'user', 'likes'])
                ->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'recette' => $recette
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Recette non trouvée'
            ], 404);
        }
    }
}