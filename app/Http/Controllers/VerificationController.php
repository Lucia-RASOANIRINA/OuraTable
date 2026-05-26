<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function show(Request $request)
    {
        return $request->user()->hasVerifiedEmail()
            ? redirect()->route('user.home')
            : view('auth.verify');
    }

    public function verify(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);

        if (!hash_equals(sha1($user->getEmailForVerification()), $hash)) {
            return redirect()->route('login')->with('modal_error', true)
                ->with('modal_title', 'Lien invalide')
                ->with('modal_message', 'Le lien de vérification est invalide.');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('login')->with('modal_warning', true)
                ->with('modal_title', 'Déjà vérifié')
                ->with('modal_message', 'Votre email est déjà vérifié. Vous pouvez vous connecter.');
        }

        $user->markEmailAsVerified();

        return redirect()->route('login')->with('modal_success', true)
            ->with('modal_title', 'Email vérifié !')
            ->with('modal_message', 'Votre compte est maintenant actif. Bienvenue sur OURATABLE !');
    }

    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('user.home');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('modal_success', true)
            ->with('modal_title', 'Email envoyé')
            ->with('modal_message', 'Un nouveau lien de vérification a été envoyé à votre adresse email.');
    }
}