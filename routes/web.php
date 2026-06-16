<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RecetteController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\ProfileController; 
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\UserRecetteController;

// Routes publiques
Route::get('/', [RecetteController::class, 'index']);

Route::get('/login', function () {
    return view('page.login'); 
})->name('login');

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout.get');

// Routes de vérification email
Route::get('/email/verify', [VerificationController::class, 'show'])->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');
Route::post('/email/resend', [VerificationController::class, 'resend'])->name('verification.resend');

// Routes protégées par authentification
Route::middleware(['auth'])->group(function () {
    Route::get('/UserHome', [RecetteController::class, 'userIndex'])->name('user.home');
    Route::post('/recettes/{id}/like', [RecetteController::class, 'like']);
});

// Routes protégées par authentification ET vérification email
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/community', [CommunityController::class, 'index'])->name('community.public');
    Route::get('/UserCommunity', [CommunityController::class, 'userCommunity'])->name('community.user');

    Route::post('/community/post', [CommunityController::class, 'store']);
    Route::post('/community/comment', [CommunityController::class, 'comment']);
    Route::post('/community/like/{id}', [CommunityController::class, 'like']);
    
    Route::put('/community/comment/{id}', [CommunityController::class, 'updateComment']);
    Route::delete('/community/comment/{id}', [CommunityController::class, 'deleteComment']);
    
    Route::post('/community/start-conversation', [CommunityController::class, 'startConversation']);
    Route::get('/community/messages/{conversationId}', [CommunityController::class, 'getMessages']);
    Route::post('/community/send-message', [CommunityController::class, 'sendMessage']);
    Route::get('/community/conversations', [CommunityController::class, 'getConversations']);
    Route::post('/community/mark-as-read/{conversationId}', [CommunityController::class, 'markAsRead']);
    Route::get('/community/users', [CommunityController::class, 'getUsers']);
    
    Route::post('/community/join-group/{groupId}', [CommunityController::class, 'joinGroup']);
    Route::post('/community/leave-group/{groupId}', [CommunityController::class, 'leaveGroup']);
    Route::get('/community/group-messages/{groupId}', [CommunityController::class, 'getGroupMessages']);
    Route::post('/community/send-group-message', [CommunityController::class, 'sendGroupMessage']);
    Route::get('/community/groups', [CommunityController::class, 'getGroups']);
    Route::get('/community/group-members/{groupId}', [CommunityController::class, 'getGroupMembers']);
    Route::post('/community/start-group-conversation/{groupId}', [CommunityController::class, 'startGroupConversation']);
    Route::post('/community/generate-ai-challenge', [CommunityController::class, 'generateNewAiChallenge']);

    Route::put('/community/post/{id}', [CommunityController::class, 'updatePost']);
    Route::delete('/community/post/{id}', [CommunityController::class, 'deletePost']);

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password');
    Route::post('/profile/upload-avatar', [ProfileController::class, 'uploadAvatar'])->name('profile.upload-avatar');

    // Routes pour les recettes utilisateur
    Route::get('/mes-recettes', [UserRecetteController::class, 'index'])->name('user.recettes');
    Route::post('/mes-recettes', [UserRecetteController::class, 'store'])->name('user.recettes.store');
    Route::get('/mes-recettes/{id}', [UserRecetteController::class, 'show'])->name('user.recettes.show');
    Route::put('/mes-recettes/{id}', [UserRecetteController::class, 'update'])->name('user.recettes.update');
    Route::delete('/mes-recettes/{id}', [UserRecetteController::class, 'destroy'])->name('user.recettes.destroy');

    Route::get('/recettes/{id}', [RecetteController::class, 'show'])->name('recettes.show');

   Route::get('/recettes/{id}/details', [RecetteController::class, 'getDetails'])->name('recettes.details');

   Route::post('/profile/delete-avatar', [ProfileController::class, 'deleteAvatar'])->name('profile.delete-avatar');
});