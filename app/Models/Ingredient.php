<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ingredient extends Model
{
    use HasFactory;

    // Définir les champs remplissables
    protected $fillable = [
        'recette_id',
        'nom',
        'quantite'
    ];

    // Relation avec Recette
    public function recette(): BelongsTo
    {
        return $this->belongsTo(Recette::class);
    }
}