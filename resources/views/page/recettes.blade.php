@extends('layouts.UserHeader')

@section('content')
<style>
    [x-cloak] { display: none !important; }
    
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-8px); }
    }
    
    .btn-floating {
        animation: float 2s ease-in-out infinite;
    }
    
    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: scale(0.95) translateY(-20px);
        }
        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }
    
    .modal-enter {
        animation: modalSlideIn 0.3s ease-out;
    }
    
    .form-input:focus {
        box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.1);
        border-color: #f97316;
        outline: none;
    }
    
    .form-input.error {
        border-color: #ef4444;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
    }
    
    .modal-scroll {
        overflow-y: auto;
        max-height: 90vh;
    }
    
    .modal-scroll::-webkit-scrollbar {
        width: 4px;
    }
    
    .modal-scroll::-webkit-scrollbar-track {
        background: transparent;
    }
    
    .modal-scroll::-webkit-scrollbar-thumb {
        background: #f97316;
        border-radius: 10px;
    }
    
    @media (max-width: 640px) {
        .btn-floating {
            width: 56px;
            height: 56px;
            bottom: 20px;
            right: 20px;
        }
        
        .btn-floating i {
            width: 24px;
            height: 24px;
        }
        
        .modal-enter {
            margin: 10px;
            border-radius: 20px;
        }
    }
    
    .recipe-card {
        background: white;
        border-radius: 2rem;
        overflow: visible;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.06);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid #f3f4f6;
        position: relative;
        text-align: center;
        padding: 0 1.5rem 1.25rem;
        margin-top: 70px;
    }
    
    .recipe-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 25px 50px rgba(249, 115, 22, 0.12);
        border-color: #f97316;
    }
    
    .recipe-avatar-wrapper {
        position: relative;
        margin-top: -70px;
        margin-bottom: 0.75rem;
        z-index: 1;
        transition: all 0.4s ease;
    }
    
    .recipe-card:hover .recipe-avatar-wrapper {
        margin-top: -90px;
    }
    
    .recipe-avatar {
        width: 140px;
        height: 140px;
        border-radius: 50%;
        overflow: hidden;
        margin: 0 auto;
        border: 4px solid white;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        transition: all 0.4s ease;
        position: relative;
        cursor: pointer;
        background: #fef3c7;
    }
    
    .recipe-card:hover .recipe-avatar {
        border-color: #f97316;
        transform: scale(1.05);
        box-shadow: 0 12px 35px rgba(249, 115, 22, 0.25);
    }
    
    .recipe-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    
    .recipe-card:hover .recipe-avatar img {
        transform: scale(1.1);
    }
    
    .recipe-avatar-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.4);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
        backdrop-filter: blur(3px);
        cursor: pointer;
    }
    
    .recipe-avatar:hover .recipe-avatar-overlay {
        opacity: 1;
    }
    
    .recipe-avatar-overlay span {
        color: white;
        font-size: 0.75rem;
        font-weight: 700;
        text-align: center;
        padding: 0.3rem;
        line-height: 1.2;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.2rem;
    }
    
    .recipe-avatar-overlay span i {
        width: 22px;
        height: 22px;
    }
    
    .recipe-title {
        font-weight: 800;
        font-size: 1.1rem;
        color: #1f2937;
        margin-bottom: 0.3rem;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
        position: relative;
        z-index: 0;
    }
    
    .recipe-desc {
        color: #9ca3af;
        font-size: 0.8rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        font-style: italic;
        margin-bottom: 0.5rem;
        min-height: 2.4rem;
        position: relative;
        z-index: 0;
    }
    
    .recipe-info-row {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1rem;
        margin-bottom: 0.5rem;
        flex-wrap: wrap;
        position: relative;
        z-index: 0;
    }
    
    .recipe-date {
        font-size: 0.6rem;
        color: #9ca3af;
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }
    
    .recipe-date i {
        width: 12px;
        height: 12px;
    }
    
    .recipe-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        background: #fef3c7;
        padding: 0.2rem 0.7rem;
        border-radius: 1rem;
        font-size: 0.6rem;
        font-weight: 700;
        color: #d97706;
    }
    
    .recipe-badge i {
        width: 12px;
        height: 12px;
    }
    
    .recipe-meta {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 0.75rem;
        border-top: 2px solid #f3f4f6;
        position: relative;
        z-index: 0;
    }
    
    .recipe-meta-left {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .recipe-meta-left .icon-box {
        width: 32px;
        height: 32px;
        background: #fef3c7;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #f97316;
    }
    
    .recipe-meta-left .icon-box i {
        width: 16px;
        height: 16px;
    }
    
    .recipe-meta-left .info-text {
        font-size: 0.7rem;
        font-weight: 600;
        color: #6b7280;
    }
    
    .recipe-meta-left .info-text .count {
        color: #1f2937;
        font-weight: 700;
    }
    
    .recipe-actions-group {
        display: flex;
        gap: 0.3rem;
    }
    
    .action-pill {
        padding: 0.3rem 0.7rem;
        border-radius: 2rem;
        font-size: 0.6rem;
        font-weight: 700;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.3rem;
        background: #f3f4f6;
        color: #6b7280;
    }
    
    .action-pill i {
        width: 14px;
        height: 14px;
    }
    
    .action-pill:hover {
        transform: scale(1.05);
    }
    
    .action-pill.edit:hover {
        background: #f97316;
        color: white;
    }
    
    .action-pill.delete:hover {
        background: #ef4444;
        color: white;
    }
    
    .action-pill.view:hover {
        background: #f97316;
        color: white;
    }

    .instructions-modal-content {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
        height: 100%;
    }
    
    @media (min-width: 768px) {
        .instructions-modal-content {
            flex-direction: row;
            gap: 0;
            height: 100%;
        }
        
        .instructions-image-section {
            flex: 0 0 35%;
            max-width: 35%;
            border-radius: 2rem 0 0 2rem;
            overflow: hidden;
            height: 100%;
            margin-left: 1.5rem;
        }
        
        .instructions-text-section {
            flex: 1;
            overflow-y: auto;
            max-height: 65vh;
            padding: 1.5rem 1.5rem 1.5rem 2rem;
            margin-right: 1.5rem;
        }
        
        .instructions-text-section::-webkit-scrollbar {
            width: 4px;
        }
        
        .instructions-text-section::-webkit-scrollbar-track {
            background: transparent;
        }
        
        .instructions-text-section::-webkit-scrollbar-thumb {
            background: #f97316;
            border-radius: 10px;
        }
    }
    
    .instructions-image-box {
        border-radius: 2rem 0 0 2rem;
        overflow: hidden;
        height: 100%;
        min-height: 100%;
        max-height: 100%;
        background: #fef3c7;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }
    
    .instructions-image-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
    }
    
    .instructions-image-box .image-label {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 2rem 1.5rem 1.5rem;
        background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);
        border-radius: 0 0 0 2rem;
    }
    
    .instructions-image-box .image-label h3 {
        color: white;
        font-size: 1.3rem;
        font-weight: 800;
        margin-bottom: 0.2rem;
    }
    
    .instructions-image-box .image-label p {
        color: rgba(255,255,255,0.8);
        font-size: 0.85rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .instructions-text-section .section-title {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.2em;
        color: #f97316;
        font-weight: 800;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .instruction-item {
        display: flex;
        gap: 0.75rem;
        padding: 0.7rem 0;
        border-bottom: 1px solid #f3f4f6;
        transition: all 0.3s ease;
    }
    
    .instruction-item:last-child {
        border-bottom: none;
    }
    
    .instruction-item:hover {
        padding-left: 0.5rem;
        background: #fafafa;
        border-radius: 0.5rem;
        padding-right: 0.5rem;
    }
    
    .instruction-number {
        flex-shrink: 0;
        width: 2rem;
        height: 2rem;
        background: linear-gradient(135deg, #f97316, #ea580c);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 0.75rem;
        box-shadow: 0 4px 10px rgba(249, 115, 22, 0.3);
        transition: transform 0.3s ease;
    }
    
    .instruction-item:hover .instruction-number {
        transform: scale(1.1) rotate(-5deg);
    }
    
    .instruction-text {
        flex: 1;
        padding-top: 0.1rem;
    }
    
    .instruction-text p {
        color: #374151;
        font-size: 0.9rem;
        line-height: 1.5;
        margin: 0;
    }

    .instructions-modal-body {
        height: 100%;
        max-height: calc(90vh - 70px);
        overflow: hidden;
        padding: 0;
    }
    
    .instructions-modal-body .instructions-modal-content {
        height: 100%;
    }

    .ingredients-list-modal {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-top: 0.5rem;
        margin-bottom: 1rem;
    }
    
    .ingredient-tag {
        background: #fef3c7;
        padding: 0.3rem 0.8rem;
        border-radius: 2rem;
        font-size: 0.75rem;
        color: #374151;
        display: flex;
        align-items: center;
        gap: 0.4rem;
        border: 1px solid #fde68a;
    }
    
    .ingredient-tag .qty {
        font-weight: 700;
        color: #f97316;
        font-size: 0.7rem;
    }
    
    .ingredient-tag i {
        width: 12px;
        height: 12px;
        color: #f97316;
    }

    /* Toast personnalisé avec bordure orange */
    .toast-message {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        padding: 1rem 1.5rem;
        border-radius: 1rem;
        font-weight: 600;
        font-size: 0.9rem;
        box-shadow: 0 10px 40px rgba(0,0,0,0.15);
        animation: slideIn 0.3s ease-out;
        max-width: 400px;
        background: white;
        border-left: 5px solid;
        color: #1f2937;
    }
    
    .toast-success {
        border-left-color: #10b981;
    }
    
    .toast-error {
        border-left-color: #ef4444;
    }
    
    .toast-info {
        border-left-color: #3b82f6;
    }
    
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(50px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }
    
    .empty-state .empty-icon {
        width: 120px;
        height: 120px;
        background: #fef3c7;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        color: #f97316;
    }
    
    .empty-state .empty-icon i {
        width: 60px;
        height: 60px;
    }

    /* Styles pour les champs en erreur */
    .field-error {
        border-color: #ef4444 !important;
        background-color: #fef2f2 !important;
    }
    
    .field-error:focus {
        border-color: #ef4444 !important;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1) !important;
    }
    
    .error-indicator {
        color: #ef4444;
        font-size: 0.75rem;
        margin-top: 0.25rem;
        display: none;
    }
    
    .error-indicator.show {
        display: block;
    }
    
    .label-required {
        color: #ef4444;
        margin-left: 2px;
    }

    /* Styles pour les dates dans la modale */
    .recipe-dates {
        display: flex;
        flex-direction: column;
        gap: 0.3rem;
        margin: 1rem 0;
        padding: 0.75rem;
        background: #f9fafb;
        border-radius: 0.75rem;
        border: 1px solid #f3f4f6;
    }
    
    .recipe-dates .date-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.75rem;
        color: #6b7280;
    }
    
    .recipe-dates .date-item i {
        width: 14px;
        height: 14px;
        color: #f97316;
    }
    
    .recipe-dates .date-item span {
        font-weight: 600;
        color: #1f2937;
    }
</style>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-black text-gray-900 tracking-tighter">
                Mes<span class="text-orange-500"> Recettes</span>
            </h1>
        </div>
        <span class="text-sm bg-orange-100 text-orange-600 px-3 py-1 rounded-full font-bold">{{ $recettes->count() }} recettes</span>
    </div>

    @if($recettes->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($recettes as $recette)
                @php
                    $likesCount = $recette->likes->count();
                    $totalIngredients = $recette->ingredients->count();
                    $createdAt = $recette->created_at ? $recette->created_at->format('d/m/Y') : 'Date inconnue';
                @endphp
                
                <div class="recipe-card">
                    
                    <div class="recipe-avatar-wrapper">
                        <div class="recipe-avatar" onclick="viewInstructions({{ $recette->id }})">
                            <img src="{{ asset('storage/' . $recette->image_path) }}" 
                                 alt="{{ $recette->titre }}">
                            <div class="recipe-avatar-overlay">
                                <span>
                                    <i data-lucide="utensils-crossed"></i>
                                    Découvrir
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <h3 class="recipe-title">{{ $recette->titre }}</h3>
                    <p class="recipe-desc">"{{ $recette->description }}"</p>
                    
                    <div class="recipe-info-row">
                        <div class="recipe-date">
                            <i data-lucide="calendar"></i>
                            {{ $createdAt }}
                        </div>
                        <div class="recipe-badge">
                            <i data-lucide="star"></i>
                            {{ $likesCount }} likes
                        </div>
                    </div>
                    
                    <div class="recipe-meta">
                        <div class="recipe-meta-left">
                            <div class="icon-box">
                                <i data-lucide="utensils"></i>
                            </div>
                            <div class="info-text">
                                <span class="count">{{ $totalIngredients }}</span> ingredients
                            </div>
                        </div>
                        
                        <div class="recipe-actions-group">
                            <button onclick="viewInstructions({{ $recette->id }})" 
                                    class="action-pill view" title="Découvrir">
                                <i data-lucide="utensils-crossed"></i>
                            </button>
                            <button onclick="editRecette({{ $recette->id }})" 
                                    class="action-pill edit" title="Modifier">
                                <i data-lucide="pencil"></i>
                            </button>
                            <button onclick="openDeleteModal({{ $recette->id }}, '{{ addslashes($recette->titre) }}')" 
                                    class="action-pill delete" title="Supprimer">
                                <i data-lucide="trash-2"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <div class="empty-icon">
                <i data-lucide="chef-hat"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-800 mb-2">Aucune recette</h3>
            <p class="text-gray-500 mb-6">Vous n avez pas encore partage de recette</p>
            <button onclick="openCreateModal()" 
                    class="px-6 py-3 bg-orange-500 text-white rounded-full font-semibold hover:bg-orange-600 transition-colors shadow-lg">
                Creer ma premiere recette
            </button>
        </div>
    @endif
</div>

<button onclick="openCreateModal()" 
        class="btn-floating fixed bottom-8 right-8 z-50 w-14 h-14 bg-orange-500 text-white rounded-full shadow-2xl hover:bg-orange-600 transition-all duration-300 hover:scale-110 flex items-center justify-center group">
    <i data-lucide="plus" class="w-6 h-6 group-hover:rotate-90 transition-transform duration-300"></i>
</button>

{{-- Modal de creation/edition --}}
<div id="recipeModal" 
     class="fixed inset-0 z-[100] hidden items-center justify-center p-3 sm:p-4 bg-black/50 backdrop-blur-sm"
     style="display: none;">
    
    <div class="modal-enter bg-white rounded-2xl sm:rounded-3xl w-full max-w-2xl max-h-[95vh] sm:max-h-[90vh] shadow-2xl flex flex-col">
        
        <div class="sticky top-0 bg-white rounded-t-2xl sm:rounded-t-3xl border-b border-gray-100 px-4 sm:px-6 py-3 sm:py-4 flex items-center justify-between z-10 flex-shrink-0">
            <h2 id="modalTitle" class="text-xl sm:text-2xl font-black text-gray-900">Nouvelle Recette</h2>
            <button onclick="closeModal()" class="p-2 hover:bg-gray-100 rounded-full transition-colors">
                <i data-lucide="x" class="w-5 h-5 text-gray-500"></i>
            </button>
        </div>
        
        <div class="modal-scroll flex-1 overflow-y-auto px-4 sm:px-6 py-4 sm:py-6">
            <form id="recipeForm" class="space-y-4 sm:space-y-6" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="recette_id" id="recette_id">
                <input type="hidden" name="_method" id="form_method" value="POST">
                
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1 sm:mb-2">
                        Titre <span class="label-required">*</span>
                    </label>
                    <input type="text" name="titre" id="titre" 
                           class="form-input w-full px-3 sm:px-4 py-2 sm:py-3 rounded-xl border border-gray-200 focus:border-orange-500 transition-colors text-sm sm:text-base"
                           placeholder="Ex: Tiramisu maison">
                    <div class="error-indicator" id="titre-error">Ce champ est requis</div>
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1 sm:mb-2">
                        Description <span class="label-required">*</span>
                    </label>
                    <textarea name="description" id="description" rows="2"
                              class="form-input w-full px-3 sm:px-4 py-2 sm:py-3 rounded-xl border border-gray-200 focus:border-orange-500 transition-colors text-sm sm:text-base"
                              placeholder="Une petite description de votre recette..."></textarea>
                    <div class="error-indicator" id="description-error">Ce champ est requis</div>
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1 sm:mb-2">
                        Instructions <span class="label-required">*</span>
                    </label>
                    <textarea name="instructions" id="instructions" rows="3"
                              class="form-input w-full px-3 sm:px-4 py-2 sm:py-3 rounded-xl border border-gray-200 focus:border-orange-500 transition-colors text-sm sm:text-base"
                              placeholder="Decrivez les etapes de preparation..."></textarea>
                    <div class="error-indicator" id="instructions-error">Ce champ est requis</div>
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1 sm:mb-2">
                        Photo <span class="label-required">*</span>
                    </label>
                    <div class="relative">
                        <input type="file" name="image" id="image" accept="image/*" class="hidden">
                        <div id="imagePreview" class="hidden mb-2 sm:mb-3">
                            <div class="relative inline-block">
                                <img id="previewImg" class="w-24 h-24 sm:w-32 sm:h-32 object-cover rounded-xl border-2 border-orange-500">
                                <button type="button" onclick="removeImage()" 
                                        class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600 transition-colors shadow-lg text-xs">
                                    <i data-lucide="x" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </div>
                        <button type="button" onclick="document.getElementById('image').click()"
                                id="imageUploadBtn"
                                class="w-full px-3 sm:px-4 py-2 sm:py-3 border-2 border-dashed border-gray-300 rounded-xl text-gray-500 hover:border-orange-500 hover:text-orange-500 transition-colors text-sm">
                            <i data-lucide="upload" class="w-4 h-4 sm:w-5 sm:h-5 inline mr-2"></i>
                            <span id="imageUploadText">Choisir une image</span>
                        </button>
                    </div>
                    <div class="error-indicator" id="image-error">Ce champ est requis</div>
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1 sm:mb-2">
                        Ingredients <span class="label-required">*</span>
                    </label>
                    <div id="ingredientsContainer" class="space-y-2 sm:space-y-3">
                        <div class="ingredient-item flex flex-col sm:flex-row gap-2 sm:gap-3">
                            <input type="text" name="ingredients[0][nom]" placeholder="Nom"
                                   class="flex-1 px-3 py-2 rounded-lg border border-gray-200 focus:border-orange-500 text-sm">
                            <div class="flex gap-2">
                                <input type="text" name="ingredients[0][quantite]" placeholder="Quantite"
                                       class="flex-1 sm:w-32 px-3 py-2 rounded-lg border border-gray-200 focus:border-orange-500 text-sm">
                                <button type="button" onclick="removeIngredient(this)" class="text-red-500 hover:text-red-700 flex-shrink-0">
                                    <i data-lucide="trash-2" class="w-5 h-5"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <button type="button" onclick="addIngredient()"
                            class="mt-2 sm:mt-3 text-sm text-orange-500 hover:text-orange-600 flex items-center gap-1">
                        <i data-lucide="plus-circle" class="w-4 h-4"></i>
                        Ajouter un ingredient
                    </button>
                    <div class="error-indicator" id="ingredients-error">Au moins un ingredient est requis</div>
                </div>
                
                <div class="flex flex-col sm:flex-row gap-3 pt-2 sm:pt-4 sticky bottom-0 bg-white py-3 sm:py-4 -mx-4 sm:-mx-6 px-4 sm:px-6 border-t border-gray-100">
                    <button type="submit" class="flex-1 px-4 sm:px-6 py-2.5 sm:py-3 bg-orange-500 text-white rounded-xl font-bold hover:bg-orange-600 transition-colors text-sm sm:text-base">
                        <span id="submitBtnText">Publier la recette</span>
                    </button>
                    <button type="button" onclick="closeModal()" 
                            class="px-4 sm:px-6 py-2.5 sm:py-3 border border-gray-300 rounded-xl font-bold text-gray-700 hover:bg-gray-50 transition-colors text-sm sm:text-base">
                        Annuler
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal des instructions --}}
<div id="instructionsModal" 
     class="fixed inset-0 z-[200] hidden items-center justify-center p-0 sm:p-4 bg-black/70 backdrop-blur-md"
     style="display: none;">
    
    <div class="modal-enter bg-white rounded-3xl w-full max-w-5xl max-h-[95vh] shadow-2xl overflow-hidden flex flex-col">
        
        <div class="flex-shrink-0 bg-white/95 backdrop-blur-sm border-b border-gray-100 px-6 py-4 flex items-center justify-between z-10">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center">
                    <i data-lucide="chef-hat" class="w-5 h-5 text-orange-500"></i>
                </div>
                <div>
                    <h2 id="instructionsTitle" class="text-xl font-black text-gray-900">Preparation</h2>
                    <p class="text-xs text-gray-500" id="instructionsSubtitle">Decouvrez les etapes de cette recette</p>
                </div>
            </div>
            <button onclick="closeInstructionsModal()" class="p-2 hover:bg-gray-100 rounded-full transition-colors">
                <i data-lucide="x" class="w-5 h-5 text-gray-500"></i>
            </button>
        </div>
        
        <div class="instructions-modal-body flex-1 overflow-hidden">
            <div id="instructionsContent" class="instructions-modal-content"></div>
        </div>
    </div>
</div>

{{-- Modal de confirmation de suppression --}}
<div id="deleteModal" 
     class="fixed inset-0 z-[300] hidden items-center justify-center p-3 sm:p-4 bg-black/60 backdrop-blur-sm"
     style="display: none;">
    
    <div class="modal-enter bg-white rounded-2xl sm:rounded-3xl w-full max-w-md shadow-2xl overflow-hidden">
        
        <div class="flex items-center justify-center pt-8">
            <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center">
                <i data-lucide="trash-2" class="w-10 h-10 text-red-500"></i>
            </div>
        </div>
        
        <div class="text-center px-6 pt-4 pb-2">
            <h3 class="text-2xl font-black text-gray-900">Confirmer la suppression</h3>
            <p class="text-gray-500 text-sm mt-2">
                Etes-vous sur de vouloir supprimer la recette
                <span id="deleteRecetteTitle" class="font-bold text-gray-800 block mt-1"></span>
            </p>
        </div>
        
        <div class="mx-6 my-4 p-3 bg-red-50 rounded-xl border border-red-100">
            <p class="text-xs text-red-600 flex items-center gap-2">
                <i data-lucide="alert-triangle" class="w-4 h-4"></i>
                Cette action est irreversible.
            </p>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-3 px-6 pb-6">
            <button onclick="confirmDelete()" 
                    class="flex-1 px-4 py-3 bg-red-500 text-white rounded-xl font-bold hover:bg-red-600 transition-colors">
                <i data-lucide="trash-2" class="w-4 h-4 inline mr-2"></i>
                Supprimer
            </button>
            <button onclick="closeDeleteModal()" 
                    class="flex-1 px-4 py-3 border border-gray-300 rounded-xl font-bold text-gray-700 hover:bg-gray-50 transition-colors">
                Annuler
            </button>
        </div>
    </div>
</div>

<script>
var ingredientIndex = 1;
var deleteRecetteId = null;
var isEditing = false;

function showToast(message, type) {
    type = type || 'success';
    var icons = {
        success: '✓',
        error: '✕',
        info: 'ℹ'
    };
    
    var existingToasts = document.querySelectorAll('.toast-message');
    existingToasts.forEach(function(t) { t.remove(); });
    
    var toast = document.createElement('div');
    toast.className = 'toast-message toast-' + type;
    toast.innerHTML = '<span style="font-weight:700;margin-right:8px;">' + icons[type] + '</span> ' + message;
    document.body.appendChild(toast);
    
    setTimeout(function() {
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(50px)';
        setTimeout(function() { toast.remove(); }, 300);
    }, 3500);
}

function addIngredient() {
    var container = document.getElementById('ingredientsContainer');
    var newDiv = document.createElement('div');
    newDiv.className = 'ingredient-item flex flex-col sm:flex-row gap-2 sm:gap-3';
    newDiv.innerHTML = `
        <input type="text" name="ingredients[${ingredientIndex}][nom]" placeholder="Nom"
               class="flex-1 px-3 py-2 rounded-lg border border-gray-200 focus:border-orange-500 text-sm">
        <div class="flex gap-2">
            <input type="text" name="ingredients[${ingredientIndex}][quantite]" placeholder="Quantite"
                   class="flex-1 sm:w-32 px-3 py-2 rounded-lg border border-gray-200 focus:border-orange-500 text-sm">
            <button type="button" onclick="removeIngredient(this)" class="text-red-500 hover:text-red-700 flex-shrink-0">
                <i data-lucide="trash-2" class="w-5 h-5"></i>
            </button>
        </div>
    `;
    container.appendChild(newDiv);
    ingredientIndex++;
    
    if (window.lucide) lucide.createIcons();
}

function removeIngredient(btn) {
    var container = document.getElementById('ingredientsContainer');
    if (container.children.length > 1) {
        btn.closest('.ingredient-item').remove();
    }
}

function clearErrors() {
    document.querySelectorAll('.field-error').forEach(function(el) {
        el.classList.remove('field-error');
    });
    document.querySelectorAll('.error-indicator').forEach(function(el) {
        el.classList.remove('show');
    });
}

function showFieldError(fieldId) {
    var field = document.getElementById(fieldId);
    if (field) {
        field.classList.add('field-error');
        var errorEl = document.getElementById(fieldId + '-error');
        if (errorEl) {
            errorEl.classList.add('show');
        }
    }
}

function openCreateModal() {
    isEditing = false;
    clearErrors();
    document.getElementById('modalTitle').textContent = 'Nouvelle Recette';
    document.getElementById('submitBtnText').textContent = 'Publier la recette';
    document.getElementById('form_method').value = 'POST';
    document.getElementById('recette_id').value = '';
    document.getElementById('recipeForm').reset();
    document.getElementById('imagePreview').classList.add('hidden');
    document.getElementById('imageUploadText').textContent = 'Choisir une image';
    
    var container = document.getElementById('ingredientsContainer');
    container.innerHTML = `
        <div class="ingredient-item flex flex-col sm:flex-row gap-2 sm:gap-3">
            <input type="text" name="ingredients[0][nom]" placeholder="Nom"
                   class="flex-1 px-3 py-2 rounded-lg border border-gray-200 focus:border-orange-500 text-sm">
            <div class="flex gap-2">
                <input type="text" name="ingredients[0][quantite]" placeholder="Quantite"
                       class="flex-1 sm:w-32 px-3 py-2 rounded-lg border border-gray-200 focus:border-orange-500 text-sm">
                <button type="button" onclick="removeIngredient(this)" class="text-red-500 hover:text-red-700 flex-shrink-0">
                    <i data-lucide="trash-2" class="w-5 h-5"></i>
                </button>
            </div>
        </div>
    `;
    ingredientIndex = 1;
    
    document.getElementById('recipeModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
    if (window.lucide) lucide.createIcons();
}

async function editRecette(id) {
    try {
        var response = await fetch('/mes-recettes/' + id);
        
        if (!response.ok) {
            throw new Error('Erreur HTTP: ' + response.status);
        }
        
        var data = await response.json();
        
        if (!data.success) {
            throw new Error(data.message || 'Erreur lors du chargement');
        }
        
        var recette = data.recette;
        
        isEditing = true;
        clearErrors();
        document.getElementById('modalTitle').textContent = 'Modifier la Recette';
        document.getElementById('submitBtnText').textContent = 'Mettre a jour';
        document.getElementById('form_method').value = 'PUT';
        document.getElementById('recette_id').value = id;
        document.getElementById('titre').value = recette.titre || '';
        document.getElementById('description').value = recette.description || '';
        document.getElementById('instructions').value = recette.instructions || '';
        document.getElementById('imageUploadText').textContent = 'Changer l image';
        
        if (recette.image_path) {
            document.getElementById('previewImg').src = '/storage/' + recette.image_path;
            document.getElementById('imagePreview').classList.remove('hidden');
        } else {
            document.getElementById('imagePreview').classList.add('hidden');
        }
        
        var container = document.getElementById('ingredientsContainer');
        container.innerHTML = '';
        if (recette.ingredients && recette.ingredients.length > 0) {
            recette.ingredients.forEach(function(ing, index) {
                var newDiv = document.createElement('div');
                newDiv.className = 'ingredient-item flex flex-col sm:flex-row gap-2 sm:gap-3';
                newDiv.innerHTML = `
                    <input type="text" name="ingredients[${index}][nom]" value="${ing.nom}" placeholder="Nom"
                           class="flex-1 px-3 py-2 rounded-lg border border-gray-200 focus:border-orange-500 text-sm">
                    <div class="flex gap-2">
                        <input type="text" name="ingredients[${index}][quantite]" value="${ing.quantite}" placeholder="Quantite"
                               class="flex-1 sm:w-32 px-3 py-2 rounded-lg border border-gray-200 focus:border-orange-500 text-sm">
                        <button type="button" onclick="removeIngredient(this)" class="text-red-500 hover:text-red-700 flex-shrink-0">
                            <i data-lucide="trash-2" class="w-5 h-5"></i>
                        </button>
                    </div>
                `;
                container.appendChild(newDiv);
            });
            ingredientIndex = recette.ingredients.length;
        } else {
            container.innerHTML = `
                <div class="ingredient-item flex flex-col sm:flex-row gap-2 sm:gap-3">
                    <input type="text" name="ingredients[0][nom]" placeholder="Nom"
                           class="flex-1 px-3 py-2 rounded-lg border border-gray-200 focus:border-orange-500 text-sm">
                    <div class="flex gap-2">
                        <input type="text" name="ingredients[0][quantite]" placeholder="Quantite"
                               class="flex-1 sm:w-32 px-3 py-2 rounded-lg border border-gray-200 focus:border-orange-500 text-sm">
                        <button type="button" onclick="removeIngredient(this)" class="text-red-500 hover:text-red-700 flex-shrink-0">
                            <i data-lucide="trash-2" class="w-5 h-5"></i>
                        </button>
                    </div>
                </div>
            `;
            ingredientIndex = 1;
        }
        
        document.getElementById('recipeModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
        if (window.lucide) lucide.createIcons();
    } catch (error) {
        console.error('Erreur:', error);
        showToast('Impossible de charger la recette', 'error');
    }
}

function closeModal() {
    document.getElementById('recipeModal').style.display = 'none';
    document.body.style.overflow = 'auto';
    document.getElementById('recipeForm').reset();
    document.getElementById('imagePreview').classList.add('hidden');
    clearErrors();
    isEditing = false;
}

function removeImage() {
    document.getElementById('image').value = '';
    document.getElementById('imagePreview').classList.add('hidden');
    document.getElementById('imageUploadText').textContent = 'Choisir une image';
}

function splitInstructions(text) {
    if (!text) return [];
    var steps = text.split(/[.,;]\s+|\n+/).filter(function(step) {
        return step.trim() !== '';
    });
    if (steps.length <= 1 && !text.includes('.') && !text.includes(',') && !text.includes(';')) {
        return [text.trim()];
    }
    return steps.map(function(step) { return step.trim(); });
}

async function viewInstructions(id) {
    try {
        var response = await fetch('/mes-recettes/' + id);
        
        if (!response.ok) {
            throw new Error('Erreur HTTP: ' + response.status);
        }
        
        var data = await response.json();
        
        if (!data.success) {
            throw new Error(data.message || 'Erreur lors du chargement');
        }
        
        var recette = data.recette;
        
        var createdDate = recette.created_at ? new Date(recette.created_at).toLocaleDateString('fr-FR', {
            day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit'
        }) : 'Date inconnue';
        
        var updatedDate = recette.updated_at ? new Date(recette.updated_at).toLocaleDateString('fr-FR', {
            day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit'
        }) : 'Date inconnue';
        
        document.getElementById('instructionsTitle').textContent = recette.titre;
        document.getElementById('instructionsSubtitle').textContent = recette.description || 'Decouvrez les etapes de cette recette';
        
        var ingredientsHtml = '';
        if (recette.ingredients && recette.ingredients.length > 0) {
            ingredientsHtml = recette.ingredients.map(function(ing) {
                return '<span class="ingredient-tag"><i data-lucide="circle"></i> ' + ing.nom + ' <span class="qty">' + ing.quantite + '</span></span>';
            }).join('');
        } else {
            ingredientsHtml = '<span class="text-gray-400 text-sm">Aucun ingredient</span>';
        }
        
        var steps = splitInstructions(recette.instructions);
        var instructionsHtml = '';
        if (steps.length > 0) {
            instructionsHtml = steps.map(function(step, index) {
                return '<div class="instruction-item"><div class="instruction-number">' + (index + 1) + '</div><div class="instruction-text"><p>' + step + '</p></div></div>';
            }).join('');
        } else {
            instructionsHtml = '<p class="text-gray-500 text-center py-8">Aucune instruction disponible</p>';
        }
        
        var container = document.getElementById('instructionsContent');
        container.innerHTML = `
            <div class="instructions-image-section">
                <div class="instructions-image-box">
                    <img src="/storage/${recette.image_path}" alt="${recette.titre}">
                    <div class="image-label">
                        <h3>${recette.titre}</h3>
                        <p>${recette.description || ''}</p>
                    </div>
                </div>
            </div>
            <div class="instructions-text-section">
                <div class="section-title">
                    <i data-lucide="book-open" class="w-4 h-4"></i>
                    Etapes de preparation
                </div>
                ${instructionsHtml}
                
                <div class="recipe-dates">
                    <div class="date-item">
                        <i data-lucide="calendar"></i>
                        Publiée le : <span>${createdDate}</span>
                    </div>
                    <div class="date-item">
                        <i data-lucide="clock"></i>
                        Modifiée le : <span>${updatedDate}</span>
                    </div>
                </div>
                
                <div style="padding-top: 0.5rem; border-top: 2px solid #f3f4f6;">
                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                        <i data-lucide="utensils" class="w-4 h-4 text-orange-500"></i>
                        <span style="font-weight: 700; font-size: 0.8rem; color: #374151;">Ingredients</span>
                    </div>
                    <div class="ingredients-list-modal">
                        ${ingredientsHtml}
                    </div>
                </div>
            </div>
        `;
        
        document.getElementById('instructionsModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
        
        if (window.lucide) lucide.createIcons();
    } catch (error) {
        console.error('Erreur:', error);
        showToast('Impossible de charger les instructions', 'error');
    }
}

function closeInstructionsModal() {
    document.getElementById('instructionsModal').style.display = 'none';
    document.body.style.overflow = 'auto';
}

function openDeleteModal(id, titre) {
    deleteRecetteId = id;
    document.getElementById('deleteRecetteTitle').textContent = '"' + titre + '" ?';
    document.getElementById('deleteModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
    if (window.lucide) lucide.createIcons();
}

function closeDeleteModal() {
    document.getElementById('deleteModal').style.display = 'none';
    document.body.style.overflow = 'auto';
    deleteRecetteId = null;
}

async function confirmDelete() {
    if (!deleteRecetteId) return;
    
    try {
        var response = await fetch('/mes-recettes/' + deleteRecetteId, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });
        
        var data = await response.json();
        
        if (data.success) {
            closeDeleteModal();
            showToast(data.message, 'success');
            setTimeout(function() { window.location.reload(); }, 1000);
        } else {
            showToast(data.message || 'Erreur lors de la suppression', 'error');
        }
    } catch (error) {
        console.error('Erreur:', error);
        showToast('Erreur lors de la suppression', 'error');
    }
}

document.getElementById('image').addEventListener('change', function(e) {
    if (e.target.files && e.target.files[0]) {
        var reader = new FileReader();
        reader.onload = function(ev) {
            document.getElementById('previewImg').src = ev.target.result;
            document.getElementById('imagePreview').classList.remove('hidden');
            document.getElementById('imageUploadText').textContent = 'Changer l image';
            document.getElementById('image').classList.remove('field-error');
            document.getElementById('image-error').classList.remove('show');
        };
        reader.readAsDataURL(e.target.files[0]);
    }
});

document.getElementById('recipeForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    clearErrors();
    
    var formData = new FormData(this);
    var recetteId = document.getElementById('recette_id').value;
    var method = document.getElementById('form_method').value;
    
    var hasError = false;
    var fields = ['titre', 'description', 'instructions'];
    
    fields.forEach(function(field) {
        var value = document.getElementById(field).value.trim();
        if (!value) {
            showFieldError(field);
            hasError = true;
        }
    });
    
    if (!isEditing) {
        var imageInput = document.getElementById('image');
        if (!imageInput.files || imageInput.files.length === 0) {
            showFieldError('image');
            hasError = true;
        }
    }
    
    var ingredientInputs = document.querySelectorAll('#ingredientsContainer input[name$="[nom]"]');
    var hasIngredients = false;
    ingredientInputs.forEach(function(input) {
        if (input.value.trim() !== '') {
            hasIngredients = true;
        }
    });
    if (!hasIngredients) {
        document.getElementById('ingredients-error').classList.add('show');
        hasError = true;
    }
    
    if (hasError) {
        showToast('Veuillez remplir tous les champs obligatoires', 'error');
        return;
    }
    
    var url = '/mes-recettes';
    var fetchMethod = 'POST';
    
    if (method === 'PUT' && recetteId) {
        url = '/mes-recettes/' + recetteId;
        fetchMethod = 'POST';
        formData.append('_method', 'PUT');
    }
    
    try {
        var response = await fetch(url, {
            method: fetchMethod,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: formData
        });
        
        var data = await response.json();
        
        if (data.success) {
            closeModal();
            showToast(data.message, 'success');
            setTimeout(function() { window.location.reload(); }, 1000);
        } else {
            if (data.errors) {
                for (var field in data.errors) {
                    if (field === 'ingredients') {
                        document.getElementById('ingredients-error').classList.add('show');
                    } else if (field === 'image') {
                        showFieldError('image');
                    } else {
                        showFieldError(field);
                    }
                }
                showToast('Veuillez corriger les erreurs', 'error');
            } else {
                showToast(data.message || 'Une erreur est survenue', 'error');
            }
        }
    } catch (error) {
        console.error('Erreur:', error);
        showToast('Une erreur est survenue', 'error');
    }
});

document.addEventListener('DOMContentLoaded', function() {
    if (window.lucide) lucide.createIcons();
});
</script>

@endsection