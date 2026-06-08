<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\Student;
use App\Models\Formation;
use App\Models\Seance;

class Group extends Model
{
    protected $fillable = [
        'name', 'month_date', 'formation_id', 'notes',
        'jour_semaine', 'slot_heure_debut', 'slot_heure_fin', 'date_fin',
    ];

    protected $casts = [
        'month_date' => 'date',
        'date_fin'   => 'date',
    ];

    // Days labels FR
    const JOURS = [
        'lundi'    => 'Lundi',
        'mardi'    => 'Mardi',
        'mercredi' => 'Mercredi',
        'jeudi'    => 'Jeudi',
        'vendredi' => 'Vendredi',
        'samedi'   => 'Samedi',
    ];

    public function students()
    {
        return $this->belongsToMany(Student::class)->withTimestamps();
    }

    public function formation()
    {
        return $this->belongsTo(Formation::class);
    }

    public function seances()
    {
        return $this->hasMany(Seance::class)->orderBy('date_seance')->orderBy('heure_debut');
    }

    /**
     * Is this group's slot still active (not finished)?
     */
    public function isActive(): bool
    {
        if (!$this->jour_semaine) return false;
        if (!$this->date_fin) return true; // no end date = still active
        return $this->date_fin->isFuture() || $this->date_fin->isToday();
    }

    /**
     * Formatted slot string, e.g. "Mardi 09:00–11:00"
     */
    public function slotLabel(): ?string
    {
        if (!$this->jour_semaine) return null;
        $label = self::JOURS[$this->jour_semaine] ?? ucfirst($this->jour_semaine);
        if ($this->slot_heure_debut) {
            $label .= ' ' . Carbon::parse($this->slot_heure_debut)->format('H:i');
            if ($this->slot_heure_fin) {
                $label .= '–' . Carbon::parse($this->slot_heure_fin)->format('H:i');
            }
        }
        return $label;
    }

    /**
     * Find conflicting groups for a given slot (same day + overlapping hours),
     * optionally excluding a specific group id.
     */
    public static function conflicts(string $jour, string $heureDebut, ?string $heureFin, ?int $excludeId = null): \Illuminate\Database\Eloquent\Collection
    {
        // Only check against groups that are still active (date_fin null or future)
        $query = static::where('jour_semaine', $jour)
            ->where(function ($q) {
                $q->whereNull('date_fin')
                  ->orWhereDate('date_fin', '>=', now()->toDateString());
            })
            ->whereNotNull('slot_heure_debut');

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        // Time overlap: existing.start < new.end AND existing.end > new.start
        $query->where(function ($q) use ($heureDebut, $heureFin) {
            if ($heureFin) {
                // Full overlap check
                $q->where(function ($inner) use ($heureDebut, $heureFin) {
                    $inner->where('slot_heure_debut', '<', $heureFin)
                          ->where(function ($i2) use ($heureDebut) {
                              $i2->whereNull('slot_heure_fin')
                                 ->orWhere('slot_heure_fin', '>', $heureDebut);
                          });
                });
            } else {
                // No end time given: just check if same start
                $q->where('slot_heure_debut', $heureDebut);
            }
        });

        return $query->with('formation')->get();
    }
}