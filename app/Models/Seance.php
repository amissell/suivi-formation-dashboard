<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seance extends Model
{
    protected $fillable = [
        'group_id', 'titre', 'date_seance',
        'heure_debut', 'heure_fin', 'type',
        'lien_zoom', 'description', 'statut',
    ];

    protected $casts = [
        'date_seance' => 'date',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}