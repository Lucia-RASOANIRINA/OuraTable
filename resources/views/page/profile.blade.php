@extends('layouts.UserHeader')

@section('content')
<style>
    [x-cloak] { display: none !important; }

    @keyframes floatIn {
        0% { opacity: 0; transform: translateY(30px); }
        100% { opacity: 1; transform: translateY(0); }
    }

    @keyframes modalPop {
        0% { opacity: 0; transform: scale(0.95); }
        100% { opacity: 1; transform: scale(1); }
    }

    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }

    @keyframes heartBeat {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }

    .animate-float-in { animation: floatIn 0.6s ease forwards; }
    .animate-modal-pop { animation: modalPop 0.3s ease forwards; }
    .animate-heart { animation: heartBeat 0.5s ease; }

    /* Avatar */
    .profile-avatar {
        width: 140px;
        height: 140px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid white;
        box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }
    .profile-avatar:hover { transform: scale(1.02); }

    .avatar-overlay {
        position: absolute;
        bottom: 5px;
        right: 5px;
        background: #f97316;
        border-radius: 50%;
        padding: 10px;
        cursor: pointer;
        transition: all 0.3s;
        border: 3px solid white;
        box-shadow: 0 5px 15px rgba(0,0,0,0.15);
    }
    .avatar-overlay:hover { background: #ea580c; transform: scale(1.1); }

    /* Cartes statistiques */
    .stat-card {
        background: white;
        border-radius: 1rem;
        padding: 1.2rem;
        text-align: center;
        transition: all 0.3s ease;
        border: 1px solid #e5e7eb;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .stat-card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1); border-color: #f97316; }

    /* Badges */
    .badge-item {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 40px;
        font-size: 11px;
        font-weight: 600;
        transition: all 0.2s;
        background: #fff7ed;
        color: #ea580c;
        border: 1px solid #fed7aa;
    }
    .badge-item:hover { transform: translateY(-2px); background: #ffedd5; }

    /* Barre de progression */
    .level-card {
        background: white;
        border-radius: 1rem;
        padding: 1rem;
        border: 1px solid #e5e7eb;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .progress-bar {
        height: 8px;
        background: #f3f4f6;
        border-radius: 4px;
        overflow: hidden;
    }
    .progress-fill {
        height: 100%;
        background: #f97316;
        border-radius: 4px;
        transition: width 0.5s ease;
        position: relative;
        overflow: hidden;
    }

    /* Tabs */
    .tab-btn {
        padding: 10px 24px;
        border: none;
        background: transparent;
        cursor: pointer;
        font-weight: 600;
        color: #6b7280;
        transition: all 0.2s;
        border-bottom: 2px solid transparent;
        font-size: 14px;
    }
    .tab-btn i { margin-right: 8px; }
    .tab-btn.active { color: #f97316; border-bottom-color: #f97316; background: #fff7ed; border-radius: 12px 12px 0 0; }
    .tab-btn:hover:not(.active) { color: #f97316; background: #fff7ed; border-radius: 12px 12px 0 0; }

    /* Informations */
    .info-row {
        display: flex;
        padding: 14px 0;
        border-bottom: 1px solid #f3f4f6;
    }
    .info-row:last-child { border-bottom: none; }
    .info-label {
        width: 140px;
        font-weight: 600;
        color: #f97316;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .info-value {
        flex: 1;
        color: #374151;
        font-weight: 500;
    }

    /* Champs édition */
    .edit-field {
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 10px 14px;
        transition: all 0.2s;
        width: 100%;
        font-size: 14px;
        background: #fafafa;
    }
    .edit-field:focus {
        outline: none;
        border-color: #f97316;
        box-shadow: 0 0 0 3px rgba(249,115,22,0.1);
        background: white;
    }
    .edit-field.error { border-color: #ef4444; background: #fef2f2; }
    .error-message { color: #ef4444; font-size: 11px; margin-top: 4px; }

    /* Boutons */
    .btn-primary {
        background: #f97316;
        color: white;
        border: none;
        padding: 10px 24px;
        border-radius: 40px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-primary:hover { background: #ea580c; transform: translateY(-2px); }
    .btn-secondary {
        background: #6b7280;
        color: white;
        border: none;
        padding: 10px 24px;
        border-radius: 40px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-secondary:hover { background: #4b5563; transform: translateY(-2px); }
    .btn-outline {
        background: transparent;
        border: 1px solid #f97316;
        color: #f97316;
        padding: 8px 20px;
        border-radius: 40px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-outline:hover { background: #fff7ed; transform: translateY(-2px); }

    /* Modals */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.5);
        backdrop-filter: blur(4px);
        z-index: 1000;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .modal-content {
        background: white;
        border-radius: 1.5rem;
        max-width: 500px;
        width: 90%;
        animation: modalPop 0.3s ease;
        padding: 1.5rem;
    }

    /* Cartes coup de foudre */
    .foudre-card {
        background: linear-gradient(135deg, #fff5f5 0%, #fff 100%);
        border: 1px solid #fed7aa;
        border-radius: 1rem;
        padding: 1rem;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .foudre-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px -5px rgba(249,115,22,0.15);
        border-color: #f97316;
    }
    .foudre-card-liked {
        background: linear-gradient(135deg, #fef3c7 0%, #fff 100%);
    }
    .like-icon {
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .like-icon:hover {
        transform: scale(1.1);
    }
    .liked {
        color: #ef4444;
        fill: #ef4444;
        animation: heartBeat 0.3s ease;
    }

    /* Activités */
    .activity-item {
        padding: 12px;
        border-radius: 1rem;
        transition: all 0.2s;
        background: #fafafa;
        margin-bottom: 8px;
        border: 1px solid #f3f4f6;
    }
    .activity-item:hover { background: #fff7ed; transform: translateX(5px); }

    /* Toast */
    .toast-message {
        position: fixed;
        bottom: 30px;
        right: 30px;
        background: #22c55e;
        color: white;
        padding: 12px 24px;
        border-radius: 50px;
        z-index: 1100;
        animation: slideInRight 0.3s ease;
        box-shadow: 0 10px 25px -5px rgba(0,0,0,0.15);
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 500;
    }
    .toast-error { background: #ef4444; }

    .specialty-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        background: #fff7ed;
        color: #ea580c;
        border-radius: 40px;
        font-size: 12px;
        font-weight: 500;
        border: 1px solid #fed7aa;
    }

    .country-select {
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 10px 14px;
        background: #fafafa;
        cursor: pointer;
        width: 100%;
        font-size: 14px;
    }
    .country-select:focus { outline: none; border-color: #f97316; background: white; }

    /* Cartes publications */
    .post-card {
        border: 1px solid #f3f4f6;
        border-radius: 1rem;
        padding: 1rem;
        transition: all 0.2s;
        background: white;
    }
    .post-card:hover {
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
        border-color: #fed7aa;
    }
    .post-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-radius: 0.75rem;
        margin-bottom: 0.75rem;
    }

    /* Animation spin */
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    .animate-spin {
        animation: spin 1s linear infinite;
    }

    /* Scrollbar personnalisée */
    .liked-posts-grid {
        max-height: 500px;
        overflow-y: auto;
        padding-right: 4px;
    }
    .liked-posts-grid::-webkit-scrollbar {
        width: 6px;
    }
    .liked-posts-grid::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    .liked-posts-grid::-webkit-scrollbar-thumb {
        background: #f97316;
        border-radius: 10px;
    }
</style>

<div class="min-h-screen bg-gray-50 py-8"
     x-data="{ 
        activeTab: 'infos',
        showEditModal: false,
        showPasswordModal: false,
        showPostModal: false,
        selectedPost: null,
        loading: false,
        showToast: false,
        toastMessage: '',
        toastType: 'success',
        formData: {
            name: '{{ old('name', $user->name) }}',
            phone: '{{ $phoneData['number'] ?? '' }}',
            phone_country_code: '{{ $phoneData['country_code'] ?? '+261' }}',
            email: '{{ old('email', $user->email) }}',
            bio: '{{ old('bio', $user->bio) }}',
            city: '{{ old('city', $user->city) }}',
            birth_date: '{{ old('birth_date', $user->birth_date) }}',
            specialty: '{{ old('specialty', $user->specialty) }}'
        },
        passwordForm: {
            current_password: '',
            new_password: '',
            new_password_confirmation: ''
        },
        errors: {},
        passwordErrors: {},
        
        formatName() {
            let name = this.formData.name;
            name = name.toLowerCase().replace(/\b\w/g, c => c.toUpperCase());
            this.formData.name = name;
        },
        
        validatePhone() {
            let phone = this.formData.phone;
            let phoneRegex = /^[0-9]{8,12}$/;
            if (phone && !phoneRegex.test(phone)) {
                this.errors.phone = 'Le numéro doit contenir 8 à 12 chiffres';
            } else {
                delete this.errors.phone;
            }
        },
        
        saveProfile() {
            if (this.errors.phone) return;
            this.loading = true;
            fetch('{{ route("profile.update") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(this.formData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.showEditModal = false;
                    this.showMessage(data.message, 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    this.showMessage(data.message, 'error');
                }
                this.loading = false;
            })
            .catch(error => {
                this.loading = false;
                this.showMessage('Erreur lors de la mise à jour', 'error');
            });
        },
        
        changePassword() {
            if (!this.passwordForm.current_password) {
                this.passwordErrors.current_password = 'Mot de passe actuel requis';
                return;
            }
            if (this.passwordForm.new_password.length < 6) {
                this.passwordErrors.new_password = 'Minimum 6 caractères';
                return;
            }
            if (this.passwordForm.new_password !== this.passwordForm.new_password_confirmation) {
                this.passwordErrors.new_password_confirmation = 'Les mots de passe ne correspondent pas';
                return;
            }
            
            this.loading = true;
            fetch('{{ route("profile.change-password") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(this.passwordForm)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.showPasswordModal = false;
                    this.passwordForm = { current_password: '', new_password: '', new_password_confirmation: '' };
                    this.passwordErrors = {};
                    this.showMessage(data.message, 'success');
                } else {
                    this.showMessage(data.message, 'error');
                }
                this.loading = false;
            })
            .catch(error => {
                this.loading = false;
                this.showMessage('Erreur lors du changement de mot de passe', 'error');
            });
        },
        
        uploadAvatar(file) {
            if (!file) return;
            let formData = new FormData();
            formData.append('avatar', file);
            formData.append('_token', '{{ csrf_token() }}');
            
            fetch('{{ route("profile.upload-avatar") }}', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.querySelector('.profile-avatar').src = data.avatar + '?t=' + Date.now();
                    this.showMessage(data.message, 'success');
                }
            });
        },
        
        showMessage(message, type) {
            this.toastMessage = message;
            this.toastType = type;
            this.showToast = true;
            setTimeout(() => { this.showToast = false; }, 3000);
        },
        
        viewPostDetails(post) {
            this.selectedPost = post;
            this.showPostModal = true;
        },
        
        closePostModal() {
            this.showPostModal = false;
            this.selectedPost = null;
        }
     }">
    
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Carte de profil principale --}}
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden mb-8 border border-gray-100">
            <div class="bg-orange-500 h-24"></div>
            <div class="relative px-6 pb-6">
                <div class="flex flex-col md:flex-row items-center md:items-end gap-6 -mt-16">
                    <div class="relative">
                        <img src="{{ $user->avatar ? asset($user->avatar) : 'https://ui-avatars.com/api/?background=f97316&color=fff&bold=true&size=140&name=' . urlencode($user->name) }}" 
                             class="profile-avatar" alt="Avatar">
                        <div class="avatar-overlay" onclick="document.getElementById('avatarInput').click()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
                                <circle cx="12" cy="13" r="4"/>
                            </svg>
                        </div>
                        <form id="avatarForm" style="display: none;">
                            @csrf
                            <input type="file" name="avatar" id="avatarInput" accept="image/*" 
                                   onchange="document.querySelector('[x-data]').__x.$data.uploadAvatar(this.files[0])">
                        </form>
                    </div>
                    
                    <div class="flex-1 text-center md:text-left">
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">{{ $user->name }}</h1>
                        <p class="text-orange-600 text-sm mt-1">{{ $user->bio ?? 'Passionné(e) de cuisine' }}</p>
                        <div class="flex flex-wrap gap-2 mt-3 justify-center md:justify-start">
                            @foreach($badges as $badge)
                            <span class="badge-item" title="{{ $badge['description'] ?? '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2z"/>
                                </svg>
                                {{ $badge['name'] }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="flex gap-3">
                        <button @click="showEditModal = true" class="btn-outline text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17 3l4 4-7 7H10v-4l7-7z"/>
                                <path d="M4 20h16"/>
                            </svg>
                            Modifier
                        </button>
                        <button @click="showPasswordModal = true" class="btn-outline text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                            Mot de passe
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Cartes statistiques --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="stat-card">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-2">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                    <line x1="16" y1="13" x2="8" y2="13"/>
                    <line x1="16" y1="17" x2="8" y2="17"/>
                </svg>
                <div class="text-2xl font-bold text-gray-900">{{ $totalPosts }}</div>
                <div class="text-xs text-gray-500 mt-1">Publications</div>
            </div>
            <div class="stat-card">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-2">
                    <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
                </svg>
                <div class="text-2xl font-bold text-gray-900">{{ $totalComments }}</div>
                <div class="text-xs text-gray-500 mt-1">Commentaires</div>
            </div>
            <div class="stat-card">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-2">
                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                </svg>
                <div class="text-2xl font-bold text-gray-900">{{ $totalLikes }}</div>
                <div class="text-xs text-gray-500 mt-1">J'aime donnés</div>
            </div>
            <div class="stat-card">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-2">
                    <path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"/>
                </svg>
                <div class="text-2xl font-bold text-gray-900">{{ $totalLikesReceived }}</div>
                <div class="text-xs text-gray-500 mt-1">J'aime reçus</div>
            </div>
        </div>

        {{-- Niveau et progression --}}
        <div class="level-card mb-8">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2z"/>
                    </svg>
                    <span class="font-bold text-gray-800">Niveau {{ $level ?? 1 }}</span>
                </div>
                <span class="text-sm text-gray-500">{{ $xp ?? 0 }} / {{ $nextLevelXp ?? 1000 }} XP</span>
            </div>
            <div class="progress-bar">
                <div class="progress-fill" style="width: {{ $xpProgress ?? 0 }}%"></div>
            </div>
            <p class="text-xs text-gray-400 mt-2 flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                Gagnez de l'XP en publiant, commentant et recevant des likes !
            </p>
        </div>

        {{-- Espace COUP DE FOUDRE --}}
        <div class="mb-8">
            <div class="flex items-center gap-2 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                </svg>
                <h2 class="text-xl font-bold text-gray-800">Coup de foudre</h2>
                <span class="text-sm text-gray-500">- Mes coups de cœur culinaires</span>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Dernière recette aimée --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-red-50 to-orange-50 px-4 py-3 border-b border-gray-100">
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                            </svg>
                            <h3 class="font-semibold text-gray-800">Dernier coup de foudre</h3>
                        </div>
                    </div>
                    <div class="p-4">
                        @if(isset($lastLikedPost) && $lastLikedPost)
                        <div class="foudre-card cursor-pointer" @click="viewPostDetails(@json($lastLikedPost))">
                            @if($lastLikedPost->image)
                            <img src="{{ asset($lastLikedPost->image) }}" class="post-image" alt="Recette">
                            @endif
                            <div class="flex items-start justify-between mb-2">
                                <div>
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-xs font-medium bg-orange-50 text-orange-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M3 6h18M9 12h6M12 3v18"/>
                                        </svg>
                                        Recette
                                    </span>
                                </div>
                                <div class="flex items-center gap-1 text-red-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor" stroke="none">
                                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                                    </svg>
                                    <span class="text-xs font-medium">{{ $lastLikedPost->likes_count ?? 0 }}</span>
                                </div>
                            </div>
                            <h4 class="font-semibold text-gray-800 mb-1">{{ Str::limit($lastLikedPost->content, 80) }}</h4>
                            <p class="text-xs text-gray-500 mb-2">
                                Publié par <span class="font-medium text-orange-600">{{ $lastLikedPost->user->name ?? 'Utilisateur' }}</span> • {{ $lastLikedPost->created_at->diffForHumans() }}
                            </p>
                            <div class="flex items-center gap-3 text-xs text-gray-400">
                                <span class="flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
                                    </svg>
                                    {{ $lastLikedPost->comments_count ?? 0 }}
                                </span>
                            </div>
                        </div>
                        @else
                        <div class="text-center py-8">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#d1d5db" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-3">
                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                            </svg>
                            <p class="text-gray-400">Vous n'avez pas encore de coup de foudre</p>
                            <p class="text-xs text-gray-400 mt-1">Aimez des recettes pour les retrouver ici !</p>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Liste des publications likées --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-red-50 to-orange-50 px-4 py-3 border-b border-gray-100">
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                <polyline points="22,6 12,13 2,6"/>
                            </svg>
                            <h3 class="font-semibold text-gray-800">Mes coups de cœur</h3>
                            <span class="text-xs text-gray-400">({{ isset($likedPosts) ? $likedPosts->count() : 0 }} recettes aimées)</span>
                        </div>
                    </div>
                    <div class="liked-posts-grid max-h-96 overflow-y-auto">
                        @if(isset($likedPosts) && $likedPosts->count() > 0)
                            @foreach($likedPosts as $like)
                            <div class="foudre-card foudre-card-liked m-3 cursor-pointer" @click="viewPostDetails(@json($like->post))">
                                <div class="flex items-start gap-3">
                                    <div class="flex-shrink-0">
                                        @if($like->post && $like->post->image)
                                        <img src="{{ asset($like->post->image) }}" class="w-16 h-16 object-cover rounded-lg" alt="Miniature">
                                        @else
                                        <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                                                <circle cx="8.5" cy="8.5" r="1.5"/>
                                                <polyline points="21 15 16 10 5 21"/>
                                            </svg>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between mb-1">
                                            <span class="text-xs text-orange-500 font-medium">{{ $like->created_at->diffForHumans() }}</span>
                                            <div class="flex items-center gap-1 text-red-400">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="currentColor" stroke="none">
                                                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                                                </svg>
                                                <span class="text-xs">{{ $like->post->likes_count ?? 0 }}</span>
                                            </div>
                                        </div>
                                        <p class="text-sm text-gray-700 line-clamp-2">{{ Str::limit($like->post->content ?? '', 80) }}</p>
                                        <p class="text-xs text-gray-400 mt-1">Par {{ $like->post->user->name ?? 'Utilisateur' }}</p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <div class="text-center py-8">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#d1d5db" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-3">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                    <polyline points="22,6 12,13 2,6"/>
                                </svg>
                                <p class="text-gray-400">Aucune recette aimée pour le moment</p>
                                <p class="text-xs text-gray-400 mt-1">Explorez la communauté et likez des recettes !</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabs --}}
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100">
            <div class="border-b border-gray-100 px-4 overflow-x-auto">
                <div class="flex gap-1 min-w-max">
                    <button @click="activeTab = 'infos'" :class="activeTab === 'infos' ? 'tab-btn active' : 'tab-btn'">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline mr-1">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                        Informations
                    </button>
                    <button @click="activeTab = 'posts'" :class="activeTab === 'posts' ? 'tab-btn active' : 'tab-btn'">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline mr-1">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <polyline points="14 2 14 8 20 8"/>
                        </svg>
                        Mes publications
                    </button>
                    <button @click="activeTab = 'activities'" :class="activeTab === 'activities' ? 'tab-btn active' : 'tab-btn'">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline mr-1">
                            <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                        </svg>
                        Activités récentes
                    </button>
                </div>
            </div>

            {{-- Onglet Informations --}}
            <div x-show="activeTab === 'infos'" class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                    <div class="info-row">
                        <div class="info-label">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                            Nom complet
                        </div>
                        <div class="info-value">{{ $user->name }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.362 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.338 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>
                            </svg>
                            Téléphone
                        </div>
                        <div class="info-value">{{ $user->phone ?? 'Non renseigné' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                <polyline points="22,6 12,13 2,6"/>
                            </svg>
                            Email
                        </div>
                        <div class="info-value">{{ $user->email ?? 'Non renseigné' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                <circle cx="12" cy="10" r="3"/>
                            </svg>
                            Ville
                        </div>
                        <div class="info-value">{{ $user->city ?? 'Non renseignée' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                <line x1="16" y1="2" x2="16" y2="6"/>
                                <line x1="8" y1="2" x2="8" y2="6"/>
                                <line x1="3" y1="10" x2="21" y2="10"/>
                            </svg>
                            Date de naissance
                        </div>
                        <div class="info-value">{{ $user->birth_date ? date('d/m/Y', strtotime($user->birth_date)) : 'Non renseignée' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 12V8H6V4H4v16h8m8-8v8m0-8h-8m8 0v-4h-4"/>
                            </svg>
                            Spécialité
                        </div>
                        <div class="info-value"><span class="specialty-badge">{{ $user->specialty ?? 'Non renseignée' }}</span></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                            </svg>
                            Bio
                        </div>
                        <div class="info-value">{{ $user->bio ?? 'Non renseignée' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Membre depuis
                        </div>
                        <div class="info-value">{{ $user->created_at->format('d/m/Y') }}</div>
                    </div>
                </div>
            </div>

            {{-- Onglet Mes publications --}}
            <div x-show="activeTab === 'posts'" class="p-6">
                @if(isset($recentPosts) && $recentPosts->count() > 0)
                    <div class="space-y-3">
                        @foreach($recentPosts as $post)
                        <div class="post-card cursor-pointer" @click="viewPostDetails(@json($post))">
                            @if($post->image)
                            <img src="{{ asset($post->image) }}" class="post-image" alt="Publication">
                            @endif
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="px-2 py-1 rounded-lg text-xs font-medium
                                            {{ $post->type == 'question' ? 'bg-blue-50 text-blue-700' : '' }}
                                            {{ $post->type == 'realisation' ? 'bg-green-50 text-green-700' : '' }}
                                            {{ $post->type == 'defi' ? 'bg-purple-50 text-purple-700' : '' }}">
                                            @if($post->type == 'question') 
                                                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline mr-1">
                                                    <circle cx="12" cy="12" r="10"/>
                                                    <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
                                                    <line x1="12" y1="17" x2="12.01" y2="17"/>
                                                </svg>
                                                Question
                                            @elseif($post->type == 'defi') 
                                                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline mr-1">
                                                    <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2z"/>
                                                </svg>
                                                Défi
                                            @else 
                                                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline mr-1">
                                                    <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
                                                    <circle cx="12" cy="13" r="4"/>
                                                </svg>
                                                Réalisation
                                            @endif
                                        </span>
                                        <span class="text-xs text-gray-400">{{ $post->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-gray-700">{{ Str::limit($post->content, 100) }}</p>
                                    <div class="flex items-center gap-4 mt-2 text-sm text-gray-500">
                                        <span class="flex items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                                            </svg> {{ $post->likes_count ?? 0 }}
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
                                            </svg> {{ $post->comments_count ?? 0 }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @if($totalPosts > 5)
                    <div class="mt-4 text-center">
                        <a href="{{ route('community.user') }}" class="text-orange-500 hover:text-orange-600 text-sm">Voir toutes mes publications →</a>
                    </div>
                    @endif
                @else
                    <div class="text-center py-12">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#d1d5db" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-3">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <polyline points="14 2 14 8 20 8"/>
                        </svg>
                        <p class="text-gray-400">Vous n'avez pas encore de publication</p>
                        <a href="{{ route('community.user') }}" class="inline-block mt-3 text-orange-500 hover:text-orange-600 font-medium">Publier maintenant →</a>
                    </div>
                @endif
            </div>

            {{-- Onglet Activités --}}
            <div x-show="activeTab === 'activities'" class="p-6">
                @if(isset($recentComments) && $recentComments->count() > 0)
                    <div class="space-y-2">
                        @foreach($recentComments as $comment)
                        <div class="activity-item">
                            <div class="flex items-start gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0 mt-0.5">
                                    <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
                                </svg>
                                <div class="flex-1">
                                    <p class="text-sm text-gray-700">Vous avez commenté : <span class="font-medium">"{{ Str::limit($comment->content, 80) }}"</span></p>
                                    @if($comment->post)
                                    <p class="text-xs text-gray-400 mt-1">sur : "{{ Str::limit($comment->post->content, 50) }}"</p>
                                    @endif
                                    <span class="text-xs text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#d1d5db" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-3">
                            <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
                        </svg>
                        <p class="text-gray-400">Aucune activité récente</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- MODAL DÉTAIL PUBLICATION --}}
    <div x-show="showPostModal" x-cloak class="modal-overlay" @click.away="closePostModal()">
        <div class="modal-content" style="max-width: 600px;">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                    </svg>
                    Détail de la publication
                </h3>
                <button @click="closePostModal()" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
            </div>
            
            <template x-if="selectedPost">
                <div>
                    <template x-if="selectedPost.image">
                        <img :src="'{{ asset('') }}' + selectedPost.image" class="w-full h-64 object-cover rounded-xl mb-4">
                    </template>
                    
                    <div class="flex items-center gap-2 mb-3">
                        <span class="px-2 py-1 rounded-lg text-xs font-medium" 
                              :class="{
                                  'bg-blue-50 text-blue-700': selectedPost.type === 'question',
                                  'bg-green-50 text-green-700': selectedPost.type === 'realisation',
                                  'bg-purple-50 text-purple-700': selectedPost.type === 'defi'
                              }">
                            <span x-show="selectedPost.type === 'question'">
                                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="inline mr-1">
                                    <circle cx="12" cy="12" r="10"/>
                                    <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
                                    <line x1="12" y1="17" x2="12.01" y2="17"/>
                                </svg>
                                Question
                            </span>
                            <span x-show="selectedPost.type === 'defi'">
                                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="inline mr-1">
                                    <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2z"/>
                                </svg>
                                Défi
                            </span>
                            <span x-show="selectedPost.type === 'realisation'">
                                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="inline mr-1">
                                    <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
                                    <circle cx="12" cy="13" r="4"/>
                                </svg>
                                Réalisation
                            </span>
                        </span>
                        <span class="text-xs text-gray-400" x-text="new Date(selectedPost.created_at).toLocaleDateString('fr-FR')"></span>
                    </div>
                    
                    <p class="text-gray-800 mb-4 leading-relaxed" x-text="selectedPost.content"></p>
                    
                    <div class="flex items-center gap-4 mb-4 pt-3 border-t border-gray-100">
                        <div class="flex items-center gap-1 text-red-500">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor" stroke="none">
                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                            </svg>
                            <span class="text-sm font-medium" x-text="selectedPost.likes_count || 0"></span>
                        </div>
                        <div class="flex items-center gap-1 text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
                            </svg>
                            <span class="text-sm" x-text="selectedPost.comments_count || 0"></span>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 rounded-xl p-3">
                        <p class="text-xs text-gray-500">
                            Publié par <span class="font-medium text-orange-600" x-text="selectedPost.user?.name || 'Utilisateur'"></span>
                        </p>
                    </div>
                </div>
            </template>
            
            <div class="flex gap-3 pt-4 mt-2">
                <button @click="closePostModal()" class="flex-1 bg-orange-500 text-white py-2 rounded-xl hover:bg-orange-600 transition">
                    Fermer
                </button>
            </div>
        </div>
    </div>

    {{-- MODAL ÉDITION PROFIL --}}
    <div x-show="showEditModal" x-cloak class="modal-overlay" @click.away="showEditModal = false">
        <div class="modal-content">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 3l4 4-7 7H10v-4l7-7z"/>
                        <path d="M4 20h16"/>
                    </svg>
                    Modifier le profil
                </h3>
                <button @click="showEditModal = false" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
            </div>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nom complet</label>
                    <input type="text" x-model="formData.name" @input="formatName()" class="edit-field w-full" :class="errors.name ? 'error' : ''">
                    <div class="error-message" x-text="errors.name"></div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
                    <div class="flex gap-2">
                        <select x-model="formData.phone_country_code" class="country-select w-28">
                            @foreach($countries as $code => $name)
                            <option value="{{ $code }}">{{ $code }}</option>
                            @endforeach
                        </select>
                        <input type="tel" x-model="formData.phone" @input="validatePhone()" class="edit-field flex-1" :class="errors.phone ? 'error' : ''" placeholder="Numéro">
                    </div>
                    <div class="error-message" x-text="errors.phone"></div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" x-model="formData.email" class="edit-field w-full">
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ville</label>
                        <input type="text" x-model="formData.city" class="edit-field w-full" placeholder="Votre ville">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date de naissance</label>
                        <input type="date" x-model="formData.birth_date" class="edit-field w-full">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Spécialité culinaire</label>
                    <select x-model="formData.specialty" class="edit-field w-full">
                        <option value="">Sélectionnez une spécialité</option>
                        <option value="Pâtisserie">🍰 Pâtisserie</option>
                        <option value="Boulangerie">🥖 Boulangerie</option>
                        <option value="Cuisine du Monde">🌍 Cuisine du Monde</option>
                        <option value="Cuisine Italienne">🍝 Cuisine Italienne</option>
                        <option value="Cuisine Asiatique">🍜 Cuisine Asiatique</option>
                        <option value="Cuisine Africaine">🌍 Cuisine Africaine</option>
                        <option value="Healthy">🥗 Healthy</option>
                        <option value="Barbecue">🥩 Barbecue</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bio</label>
                    <textarea x-model="formData.bio" rows="3" class="edit-field w-full" placeholder="Parlez-nous de vous..."></textarea>
                </div>
                
                <div class="flex gap-3 pt-2">
                    <button type="button" @click="showEditModal = false" class="flex-1 px-4 py-2 border border-gray-300 rounded-xl text-gray-600 hover:bg-gray-50 transition">
                        Annuler
                    </button>
                    <button type="button" @click="saveProfile()" :disabled="loading" class="flex-1 bg-orange-500 text-white py-2 rounded-xl hover:bg-orange-600 transition">
                        <span x-show="loading" class="inline-block animate-spin rounded-full h-4 w-4 border-2 border-white border-t-transparent mr-2"></span>
                        <span x-show="!loading">Enregistrer</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL CHANGEMENT DE MOT DE PASSE --}}
    <div x-show="showPasswordModal" x-cloak class="modal-overlay" @click.away="showPasswordModal = false">
        <div class="modal-content">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                    Changer le mot de passe
                </h3>
                <button @click="showPasswordModal = false" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
            </div>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mot de passe actuel</label>
                    <input type="password" x-model="passwordForm.current_password" class="edit-field w-full">
                    <div class="error-message" x-text="passwordErrors.current_password"></div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nouveau mot de passe</label>
                    <input type="password" x-model="passwordForm.new_password" class="edit-field w-full">
                    <div class="error-message" x-text="passwordErrors.new_password"></div>
                    <p class="text-xs text-gray-400 mt-1">Minimum 6 caractères</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirmer le mot de passe</label>
                    <input type="password" x-model="passwordForm.new_password_confirmation" class="edit-field w-full">
                    <div class="error-message" x-text="passwordErrors.new_password_confirmation"></div>
                </div>
                
                <div class="flex gap-3 pt-2">
                    <button type="button" @click="showPasswordModal = false" class="flex-1 px-4 py-2 border border-gray-300 rounded-xl text-gray-600 hover:bg-gray-50 transition">
                        Annuler
                    </button>
                    <button type="button" @click="changePassword()" :disabled="loading" class="flex-1 bg-orange-500 text-white py-2 rounded-xl hover:bg-orange-600 transition">
                        <span x-show="loading" class="inline-block animate-spin rounded-full h-4 w-4 border-2 border-white border-t-transparent mr-2"></span>
                        <span x-show="!loading">Changer</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- TOAST NOTIFICATION --}}
    <div x-show="showToast" x-cloak class="toast-message" :class="toastType === 'error' ? 'toast-error' : ''">
        <svg x-show="toastType === 'success'" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="20 6 9 17 4 12"/>
        </svg>
        <svg x-show="toastType === 'error'" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="18" y1="6" x2="6" y2="18"/>
            <line x1="6" y1="6" x2="18" y2="18"/>
        </svg>
        <span x-text="toastMessage"></span>
    </div>
</div>

<script src="https://unpkg.com/lucide@latest"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>
@endsection