<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Like;
use libphonenumber\PhoneNumberUtil;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\NumberParseException;

class ProfileController extends Controller
{
    private $phoneUtil;
    
    public function __construct()
    {
        $this->phoneUtil = PhoneNumberUtil::getInstance();
    }
    
    public function index()
    {
        $user = Auth::user();
        
        $totalPosts = Post::where('user_id', $user->id)->count();
        $totalComments = Comment::where('user_id', $user->id)->count();
        $totalLikes = Like::where('user_id', $user->id)->count();
        $totalLikesReceived = Like::whereIn('post_id', Post::where('user_id', $user->id)->pluck('id'))->count();
        
        $recentPosts = Post::where('user_id', $user->id)->latest()->limit(5)->get();
        $recentComments = Comment::where('user_id', $user->id)->with('post')->latest()->limit(5)->get();
        
        $level = $user->level ?? 1;
        $xp = $user->xp ?? 0;
        $nextLevelXp = $user->next_level_xp ?? 1000;
        $xpProgress = $nextLevelXp > 0 ? ($xp / $nextLevelXp) * 100 : 0;
        
        $badges = $this->calculateBadges($user);
        
        // Générer automatiquement la liste des pays depuis libphonenumber
        $countries = $this->getCountriesFromLibrary();
        
        // Extraire l'indicatif et le numéro du téléphone existant
        $phoneData = $this->extractPhoneData($user->phone);
        
        return view('page.profile', compact(
            'user', 'totalPosts', 'totalComments', 'totalLikes', 'totalLikesReceived',
            'recentPosts', 'recentComments', 'level', 'xp', 'nextLevelXp', 'xpProgress', 
            'badges', 'countries', 'phoneData'
        ));
    }
    
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:100|regex:/^[\pL\s]+$/u',
            'phone' => 'required|string|max:20',
            'phone_country_code' => 'required|string',
            'email' => 'nullable|email|max:150|unique:users,email,' . $user->id,
            'bio' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'birth_date' => 'nullable|date|before:today',
            'specialty' => 'nullable|string|max:100',
        ]);
        
        $formattedName = ucwords(strtolower($request->name));
        
        // Valider et formater avec libphonenumber
        $fullPhone = $this->validateAndFormatPhone($request->phone_country_code, $request->phone);
        
        if (!$fullPhone) {
            return response()->json([
                'success' => false, 
                'message' => 'Numéro de téléphone invalide pour le pays sélectionné.'
            ], 422);
        }
        
        $user->update([
            'name' => $formattedName,
            'phone' => $fullPhone,
            'email' => $request->email,
            'bio' => $request->bio,
            'city' => $request->city,
            'birth_date' => $request->birth_date,
            'specialty' => $request->specialty,
        ]);
        
        return response()->json(['success' => true, 'message' => 'Profil mis à jour avec succès !']);
    }
    
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);
        
        $user = Auth::user();
        
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['success' => false, 'message' => 'Mot de passe actuel incorrect.'], 422);
        }
        
        $user->update(['password' => Hash::make($request->new_password)]);
        
        return response()->json(['success' => true, 'message' => 'Mot de passe modifié avec succès !']);
    }
    
    public function uploadAvatar(Request $request)
    {
        $request->validate(['avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048']);
        
        $user = Auth::user();
        
        if ($user->avatar && file_exists(public_path($user->avatar))) {
            unlink(public_path($user->avatar));
        }
        
        $avatarName = time() . '_' . uniqid() . '.' . $request->avatar->extension();
        $request->avatar->move(public_path('uploads/avatars'), $avatarName);
        $user->avatar = 'uploads/avatars/' . $avatarName;
        $user->save();
        
        return response()->json(['success' => true, 'message' => 'Photo de profil mise à jour !', 'avatar' => asset($user->avatar)]);
    }
    
    /**
     * Génère automatiquement la liste des pays depuis libphonenumber
     */
    private function getCountriesFromLibrary()
    {
        $countries = [];
        
        // Récupérer tous les codes pays supportés par libphonenumber
        $supportedCountries = $this->phoneUtil->getSupportedRegions();
        
        // Trier les pays par nom
        sort($supportedCountries);
        
        foreach ($supportedCountries as $countryCode) {
            try {
                // Obtenir l'indicatif du pays
                $countryCodeNumber = $this->phoneUtil->getCountryCodeForRegion($countryCode);
                if ($countryCodeNumber) {
                    $regionCode = $this->phoneUtil->getRegionCodeForCountryCode($countryCodeNumber);
                    if ($regionCode === $countryCode) {
                        $countries['+' . $countryCodeNumber] = $this->getCountryName($countryCode) . ' (+' . $countryCodeNumber . ')';
                    }
                }
            } catch (\Exception $e) {
                continue;
            }
        }
        
        // Trier par nom de pays
        asort($countries);
        
        return $countries;
    }
    
    /**
     * Convertit le code pays en nom de pays en français
     */
    private function getCountryName($countryCode)
    {
        $countryNames = [
            'CI' => 'Côte d\'Ivoire', 'SN' => 'Sénégal', 'CM' => 'Cameroun', 'ML' => 'Mali',
            'BF' => 'Burkina Faso', 'TG' => 'Togo', 'BJ' => 'Bénin', 'NE' => 'Niger',
            'GN' => 'Guinée', 'GW' => 'Guinée-Bissau', 'LR' => 'Libéria', 'SL' => 'Sierra Leone',
            'GH' => 'Ghana', 'NG' => 'Nigéria', 'FR' => 'France', 'US' => 'États-Unis',
            'CA' => 'Canada', 'GB' => 'Royaume-Uni', 'DE' => 'Allemagne', 'ES' => 'Espagne',
            'IT' => 'Italie', 'PT' => 'Portugal', 'CH' => 'Suisse', 'BE' => 'Belgique',
            'NL' => 'Pays-Bas', 'SE' => 'Suède', 'NO' => 'Norvège', 'DK' => 'Danemark',
            'FI' => 'Finlande', 'PL' => 'Pologne', 'CZ' => 'République Tchèque', 'HU' => 'Hongrie',
            'AT' => 'Autriche', 'LU' => 'Luxembourg', 'IE' => 'Irlande', 'GR' => 'Grèce',
            'TR' => 'Turquie', 'MA' => 'Maroc', 'TN' => 'Tunisie', 'DZ' => 'Algérie',
            'EG' => 'Égypte', 'ZA' => 'Afrique du Sud', 'BR' => 'Brésil', 'MX' => 'Mexique',
            'AR' => 'Argentine', 'CL' => 'Chili', 'PE' => 'Pérou', 'CO' => 'Colombie',
            'VE' => 'Venezuela', 'AU' => 'Australie', 'NZ' => 'Nouvelle-Zélande', 'JP' => 'Japon',
            'CN' => 'Chine', 'IN' => 'Inde', 'KR' => 'Corée du Sud', 'RU' => 'Russie',
        ];
        
        return $countryNames[$countryCode] ?? $countryCode;
    }
    
    /**
     * Valide et formate le numéro avec libphonenumber
     */
    private function validateAndFormatPhone($countryCodePrefix, $phoneNumber)
    {
        try {
            // Trouver le code pays à 2 lettres à partir de l'indicatif
            $countryCodeNumber = (int)str_replace('+', '', $countryCodePrefix);
            $regionCode = $this->phoneUtil->getRegionCodeForCountryCode($countryCodeNumber);
            
            if (!$regionCode) {
                return false;
            }
            
            $parsedNumber = $this->phoneUtil->parse($phoneNumber, $regionCode);
            
            if ($this->phoneUtil->isValidNumber($parsedNumber)) {
                return $this->phoneUtil->format($parsedNumber, PhoneNumberFormat::E164);
            }
            return false;
        } catch (NumberParseException $e) {
            return false;
        }
    }
    
    /**
     * Extrait l'indicatif et le numéro depuis un téléphone stocké
     */
    private function extractPhoneData($phone)
    {
        if (empty($phone)) {
            // Par défaut : Madagascar
            return ['country_code' => '+261', 'number' => ''];
        }
        
        try {
            $parsedNumber = $this->phoneUtil->parse($phone, null);
            
            if ($this->phoneUtil->isValidNumber($parsedNumber)) {
                $countryCode = '+' . $parsedNumber->getCountryCode();
                $nationalNumber = (string)$parsedNumber->getNationalNumber();
                return ['country_code' => $countryCode, 'number' => $nationalNumber];
            }
        } catch (NumberParseException $e) {
            // Ignorer
        }
        
        return ['country_code' => '+261', 'number' => ''];
    }
    
    private function calculateBadges($user)
    {
        $badges = [];
        $postCount = Post::where('user_id', $user->id)->count();
        $likeCount = Like::whereIn('post_id', Post::where('user_id', $user->id)->pluck('id'))->count();
        $memberMonths = $user->created_at->diffInMonths(now());
        
        if ($postCount >= 20) {
            $badges[] = ['name' => 'Grand Chef Étoilé', 'icon' => 'chef-hat', 'color' => 'amber'];
        } elseif ($postCount >= 10) {
            $badges[] = ['name' => 'Chef Confirmé', 'icon' => 'chef-hat', 'color' => 'orange'];
        } elseif ($postCount >= 5) {
            $badges[] = ['name' => 'Chef en Herbe', 'icon' => 'chef-hat', 'color' => 'yellow'];
        }
        
        if ($likeCount >= 100) {
            $badges[] = ['name' => 'Célébrité Culinaire', 'icon' => 'crown', 'color' => 'purple'];
        } elseif ($likeCount >= 50) {
            $badges[] = ['name' => 'Très Apprécié', 'icon' => 'heart', 'color' => 'rose'];
        } elseif ($likeCount >= 20) {
            $badges[] = ['name' => 'Apprécié', 'icon' => 'heart', 'color' => 'pink'];
        }
        
        if ($memberMonths >= 12) {
            $badges[] = ['name' => 'Ambassadeur', 'icon' => 'award', 'color' => 'purple'];
        } elseif ($memberMonths >= 6) {
            $badges[] = ['name' => 'Membre Fidèle', 'icon' => 'award', 'color' => 'blue'];
        }
        
        if ($user->specialty) {
            $badges[] = ['name' => 'Spécialiste ' . $user->specialty, 'icon' => 'sparkles', 'color' => 'gold'];
        }
        
        return $badges;
    }
}