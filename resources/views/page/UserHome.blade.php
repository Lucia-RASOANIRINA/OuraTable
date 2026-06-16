@extends('layouts.UserHeader')

@section('content')
<style>
    [x-cloak] { display: none !important; }

    @keyframes spin-slow {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    .animate-spin-slow {
        animation: spin-slow 10s linear infinite;
    }
</style>

<!-- AlpineJS pour gérer afficher/cacher -->
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8"
     x-data="{ 
            currentIndex: 0, 
            showLoginModal: false,
            total: {{ $recettes->count() }},
            next() { this.currentIndex = (this.currentIndex + 1) % this.total },
            prev() { this.currentIndex = (this.currentIndex - 1 + this.total) % this.total }
         }">
    
    {{-- SECTION HAUTE (Encapsulée pour éviter les bugs de superposition au scroll) --}}
    <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-12 mb-20">
        <div class="w-full md:w-1/2 text-center md:text-left flex flex-col justify-center">
    
            <div class="flex items-center justify-center md:justify-start gap-6 mb-8 group">
                <div class="relative shrink-0">
                    <div class="absolute -inset-2 bg-orange-500/20 rounded-full blur-xl opacity-0 group-hover:opacity-100 transition duration-500"></div>
                    <div class="relative flex items-center justify-center w-16 h-16 md:w-20 md:h-20 border-2 border-orange-500 rounded-full shadow-2xl bg-white transition-transform duration-500 group-hover:rotate-12">
                        <div class="w-12 h-12 md:w-16 md:h-16 bg-orange-500 rounded-full flex items-center justify-center shadow-inner">
                            <i data-lucide="chef-hat" class="w-7 h-7 md:w-9 md:h-9 text-white"></i>
                        </div>
                    </div>
                </div>
                
                <h2 class="text-gray-900 tracking-tighter flex-1">
                    <div class="text-4xl md:text-6xl font-black tracking-tighter text-orange-500 italic leading-none flex items-center justify-center md:justify-start whitespace-nowrap">
                        <span>L'Univers</span>
                        <span class="relative ml-3 text-gray-800 inline-block group">
                            Culinaire
                            {{-- La ligne qui s'anime de gauche à droite --}}
                            <span class="absolute -bottom-2 left-0 h-2 bg-orange-500/20 transition-all duration-1000 ease-out w-0"
                                x-init="setTimeout(() => $el.style.width = '100%', 500)"></span>
                        </span>
                    </div>
                </h2>
            </div>

            <div class="relative p-6 md:p-8 bg-white rounded-3xl shadow-lg border border-gray-200 max-w-xl mx-auto md:mx-0 group hover:shadow-2xl transition-all duration-500 hover:-translate-y-1">
                <div class="absolute -top-3 -left-3 w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center shadow-md">
                    <i data-lucide="quote" class="w-6 h-6 text-orange-500 rotate-180"></i>
                </div>

                <p class="text-gray-600 font-medium text-base md:text-lg leading-relaxed text-center md:text-left pt-4">
                    Bienvenue dans notre galerie gourmande 
                    <i data-lucide="utensils" class="inline-block w-5 h-5 text-orange-400 mb-1 mx-1 animate-pulse"></i>. 
                    Parcourez nos créations et laissez-vous inspirer par des saveurs authentiques 
                    <i data-lucide="heart" class="inline-block w-5 h-5 text-red-400 mb-1 mx-1 hover:scale-125 transition-transform"></i>, 
                    pensées pour sublimer chacun de vos instants à table 
                    <i data-lucide="sparkles" class="inline-block w-5 h-5 text-yellow-400 mb-1 mx-1 animate-spin-slow"></i>.
                </p>

                <div class="mt-6 pt-4 border-t border-gray-200 text-center md:text-right">
                    <span class="text-xs font-black uppercase tracking-[0.3em] text-gray-300">
                        Oura<span class="text-orange-500/50">Table</span>
                    </span>
                </div>
            </div>

            <div class="mt-10 flex items-center justify-center md:justify-start gap-4 group">
                <div class="h-1 w-12 bg-orange-500/30 rounded-full group-hover:w-16 transition-all duration-500"></div>
                <span class="text-[11px] font-black uppercase tracking-[0.3em] text-orange-600 italic">
                    L'art de bien manger
                </span>
            </div>
        </div>

        <div class="w-full md:w-1/2 relative flex justify-center items-center h-[450px]">
            {{-- Boutons avec z-index réduit (z-40) pour ne pas gêner le header --}}
            <button @click="prev()" 
                    class="absolute left-4 md:-left-4 z-40 p-3 bg-white/90 hover:bg-orange-500 text-gray-800 hover:text-white rounded-full shadow-2xl transition-all duration-300 active:scale-90 border border-gray-200 group">
                <i data-lucide="chevron-left" class="w-6 h-6 group-hover:-translate-x-1 transition-transform"></i>
            </button>

            <button @click="next()" 
                    class="absolute right-4 md:-right-4 z-40 p-3 bg-white/90 hover:bg-orange-500 text-gray-800 hover:text-white rounded-full shadow-2xl transition-all duration-300 active:scale-90 border border-gray-200 group">
                <i data-lucide="chevron-right" class="w-6 h-6 group-hover:translate-x-1 transition-transform"></i>
            </button>

           @if($recettes->count() > 0)
                @foreach($recettes as $index => $recette)
                    <div class="absolute transition-all duration-700 ease-[cubic-bezier(0.23,1,0.32,1)]"
                        :style="'z-index: ' + (currentIndex === {{ $index }} ? 30 : (10 - Math.abs(currentIndex - {{ $index }})))"
                        :class="{
                            'scale-110 rotate-0 opacity-100 translate-x-0': currentIndex === {{ $index }},
                            'scale-90 -rotate-12 -translate-x-32 md:-translate-x-40 opacity-40 blur-[2px]': {{ $index }} < currentIndex,
                            'scale-90 rotate-12 translate-x-32 md:translate-x-40 opacity-40 blur-[2px]': {{ $index }} > currentIndex,
                            'opacity-70 blur-[1px] translate-x-16 md:translate-x-20': ({{ $index }} === currentIndex + 1) || ({{ $index }} === currentIndex - 1)
                        }">
                        
                        <div class="relative group">
                            {{-- L'icône dynamique : elle ne s'affiche que si l'index est l'index actif --}}
                            <div x-show="currentIndex === {{ $index }}"
                                x-transition:enter="transition ease-out duration-500"
                                x-transition:enter-start="opacity-0 scale-50"
                                x-transition:enter-end="opacity-100 scale-100"
                                class="absolute -top-4 -left-4 bg-gray-900 text-white p-3 rounded-2xl shadow-xl z-[60]">
                                <i data-lucide="award" class="w-5 h-5 text-orange-400"></i>
                            </div>

                            <img src="{{ asset('storage/' . $recette->image_path) }}" 
                                class="w-48 h-64 md:w-64 md:h-80 object-cover rounded-[2.5rem] shadow-2xl border-[10px] border-white transition-all duration-300 group-hover:scale-[1.03] group-hover:border-orange-400">
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    {{-- GRILLE DES RECETTES --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 pt-10">
        @foreach($recettes as $recette)
            @php
                $isLiked = auth()->check() && $recette->likes->contains('user_id', auth()->id());
                $likesCount = $recette->likes->count();
                $rating = min(5, ($likesCount * 0.1) + 3.5);
                $totalIngredients = $recette->ingredients->count();
            @endphp

            <div class="relative bg-white rounded-[40px] shadow-sm border border-gray-100 flex overflow-hidden transition-all duration-500 hover:shadow-xl hover:-translate-y-1">
                
                {{-- IMAGE --}}
                <div class="relative w-2/5 min-w-[160px] flex items-center justify-center p-4 group">

                    <!-- Fond orange permanent -->
                    <div class="absolute inset-y-0 left-0 w-3/4 bg-orange-500 rounded-r-[40px] transition-colors duration-500 group-hover:bg-orange-600"></div>

                    <div class="relative z-10 w-32 h-32 md:w-40 md:h-40 rounded-full overflow-hidden transition-transform duration-500 group-hover:scale-105 group-hover:rotate-2">

                        <!-- Glow derrière l'image -->
                        <div class="absolute inset-2 rounded-full bg-orange-900/10 blur-xl transition-all duration-500 group-hover:bg-orange-900/20"></div>

                        <img src="{{ asset('storage/'.$recette->image_path) }}" 
                            class="relative w-full h-full object-cover rounded-full border-[6px] border-white shadow-lg transition-transform duration-500 group-hover:scale-110 group-hover:rotate-3">

                        <!-- rating -->
                        <div class="absolute -top-2 -right-2 bg-white shadow-lg px-2 py-1 rounded-full flex items-center gap-1 border border-orange-50">
                            <i data-lucide="star" class="w-3 h-3 text-yellow-400 fill-yellow-400"></i>
                            <span class="text-[10px] font-black text-gray-800">{{ number_format($rating,1) }}</span>
                        </div>

                    </div>
                </div>

                {{-- CONTENU --}}
                <div class="w-3/5 p-6 pl-2 flex flex-col justify-center">

                    {{-- TITRE --}}
                    <div class="mb-3">
                        <h3 class="font-bold text-lg text-center text-gray-900 truncate">{{ $recette->titre }}</h3>
                        <p class="text-gray-500 text-[11px] italic line-clamp-1">
                            "{{ $recette->description }}"
                        </p>
                    </div>

                    {{-- INGREDIENTS --}}
                    <div x-data="{ open:false }" class="mb-4 bg-gray-50 p-4 rounded-[24px] border border-gray-100">

                        <div class="flex items-center justify-between mb-3">

                            <div class="flex items-center gap-2">
                                <i data-lucide="utensils" class="w-3.5 h-3.5 text-orange-500"></i>
                                <span class="text-[10px] font-black text-gray-500 uppercase">
                                    {{ $totalIngredients }} ingrédients
                                </span>
                            </div>

                            {{-- bouton toggle --}}
                            @if($totalIngredients > 4)
                            <button @click="open = !open"
                                class="p-1 rounded-full hover:bg-orange-100 transition">

                                <i data-lucide="list-ordered"
                                   class="w-4 h-4 text-orange-500 transition-transform"
                                   :class="open ? 'rotate-180' : ''">
                                </i>

                            </button>
                            @endif

                        </div>

                        <ul class="space-y-2">

                            {{-- 4 premiers --}}
                            @foreach($recette->ingredients->take(4) as $ingredient)
                            <li class="text-[11px] text-gray-700 flex justify-between border-b border-gray-200 pb-1.5">
                                <span class="font-medium">• {{ $ingredient->nom }}</span>
                                <span class="font-black text-orange-600/70">{{ $ingredient->quantite }}</span>
                            </li>
                            @endforeach

                            {{-- reste caché --}}
                            @foreach($recette->ingredients->skip(4) as $ingredient)
                            <li x-show="open"
                                x-transition
                                style="display:none;"
                                class="text-[11px] text-gray-700 flex justify-between border-b border-gray-200 py-1.5">
                                <span class="font-medium">• {{ $ingredient->nom }}</span>
                                <span class="font-black text-orange-600/70">{{ $ingredient->quantite }}</span>
                            </li>
                            @endforeach

                        </ul>

                    </div>

                    {{-- FOOTER --}}
                    <div class="flex items-center justify-between mt-auto pt-4 border-t border-gray-100">

                        <div class="flex items-center gap-2">
                            <div class="user-icon w-6 h-6 rounded-full bg-orange-100 flex items-center justify-center transition-all duration-300">
                                <i data-lucide="user" class="icon-user w-3 h-3 text-orange-500 transition-all duration-300"></i>
                            </div>

                            <span class="text-[10px] font-bold text-gray-800 uppercase">
                                {{ $recette->user->name ?? 'Inconnu' }}
                            </span>
                        </div>

                        @auth
                        <button onclick="likeRecette({{ $recette->id }}, this)" 
                                class="like-btn flex items-center gap-2 px-4 py-2 rounded-full transition-all duration-300 
                                {{ $isLiked ? 'bg-red-50' : 'bg-gray-100' }}">

                            <i data-lucide="heart"
                               class="w-4 h-4 {{ $isLiked ? 'text-red-500 fill-red-500' : 'text-gray-400' }}"></i>

                            <span class="text-[10px] font-black uppercase 
                                {{ $isLiked ? 'text-red-500' : 'text-gray-500' }}">
                                J'adore
                            </span>

                        </button>
                        @endauth

                    </div>

                </div>
            </div>

        @endforeach
    </div>
</div>

<script>
function likeRecette(id, btn) {
    // Désactiver le bouton pendant la requête
    btn.disabled = true;
    
    // Chercher l'icône (peut être <i> ou <svg>)
    const icon = btn.querySelector('i') || btn.querySelector('svg');
    const span = btn.querySelector('span');
    
    // Vérifier que les éléments existent
    if (!icon || !span) {
        console.error('Éléments non trouvés:', {icon: icon, span: span});
        btn.disabled = false;
        return;
    }
    
    const isLiked = icon.classList.contains('fill-red-500');

    // Animation de l'icône
    if (!isLiked) {
        icon.classList.add('scale-110');
        setTimeout(() => {
            icon.classList.remove('scale-110');
        }, 200);
    }

    // Mise à jour UI immédiate (optimiste)
    if (isLiked) {
        icon.classList.remove('text-red-500', 'fill-red-500');
        icon.classList.add('text-gray-400');
        span.classList.remove('text-red-500');
        span.classList.add('text-gray-500');
        btn.classList.remove('bg-red-50');
        btn.classList.add('bg-gray-100');
    } else {
        icon.classList.add('text-red-500', 'fill-red-500');
        icon.classList.remove('text-gray-400');
        span.classList.add('text-red-500');
        span.classList.remove('text-gray-500');
        btn.classList.remove('bg-gray-100');
        btn.classList.add('bg-red-50');
    }

    // Envoi au serveur
    fetch(`/recettes/${id}/like`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log('Like mis à jour:', data);
    })
    .catch(error => {
        console.error('Erreur lors du like:', error);
        // En cas d'erreur, on pourrait revenir à l'état précédent
        // Mais pour simplifier, on garde l'état UI
    })
    .finally(() => {
        btn.disabled = false;
    });
}

document.addEventListener('DOMContentLoaded', () => {
    if (window.lucide) {
        lucide.createIcons();
    }
});
</script>

<style>
.like-btn i.scale-110{
animation:heartBeat 0.3s ease-out;
}

@keyframes heartBeat{
0%{transform:scale(1);}
50%{transform:scale(1.3);}
100%{transform:scale(1.1);}
}
</style>

@endsection