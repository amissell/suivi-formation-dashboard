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
        'jours_semaine', 'slot_heure_debut', 'slot_heure_fin', 'date_fin',
    ];

    protected $casts = [
        'month_date'    => 'date',
        'date_fin'      => 'date',
        'jours_semaine' => 'array',
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
        if (empty($this->jours_semaine)) return false;
        if (!$this->date_fin) return true; // no end date = still active
        return $this->date_fin->isFuture() || $this->date_fin->isToday();
    }

    /**
     * Formatted slot string, e.g. "Mardi, Jeudi 09:00–11:00"
     */
    public function slotLabel(): ?string
    {
        if (empty($this->jours_semaine)) return null;

        $days = array_map(
            fn($j) => self::JOURS[$j] ?? ucfirst($j),
            $this->jours_semaine
        );
        $label = implode(', ', $days);

        if ($this->slot_heure_debut) {
            $label .= ' ' . Carbon::parse($this->slot_heure_debut)->format('H:i');
            if ($this->slot_heure_fin) {
                $label .= '–' . Carbon::parse($this->slot_heure_fin)->format('H:i');
            }
        }
        return $label;
    }

    /**
     * Find conflicting groups for given days (same day(s) + overlapping hours),
     * optionally excluding a specific group id.
     *
     * @param array $jours e.g. ['lundi','mercredi']
     */
    public static function conflicts(array $jours, string $heureDebut, ?string $heureFin, ?int $excludeId = null): \Illuminate\Database\Eloquent\Collection
    {
        $jours = array_values(array_filter($jours));
        if (empty($jours)) {
            return new \Illuminate\Database\Eloquent\Collection();
        }

        $query = static::where(function ($q) {
                $q->whereNull('date_fin')
                  ->orWhereDate('date_fin', '>=', now()->toDateString());
            })
            ->whereNotNull('slot_heure_debut')
            ->whereNotNull('jours_semaine');

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        // Match groups that share at least one day with the requested days
        $query->where(function ($q) use ($jours) {
            foreach ($jours as $jour) {
                $q->orWhereJsonContains('jours_semaine', $jour);
            }
        });

        // Time overlap: existing.start < new.end AND existing.end > new.start
        $query->where(function ($q) use ($heureDebut, $heureFin) {
            if ($heureFin) {
                $q->where(function ($inner) use ($heureDebut, $heureFin) {
                    $inner->where('slot_heure_debut', '<', $heureFin)
                          ->where(function ($i2) use ($heureDebut) {
                              $i2->whereNull('slot_heure_fin')
                                 ->orWhere('slot_heure_fin', '>', $heureDebut);
                          });
                });
            } else {
                $q->where('slot_heure_debut', $heureDebut);
            }
        });

        return $query->with('formation')->get()->filter(function ($g) use ($jours) {
            return count(array_intersect($g->jours_semaine ?? [], $jours)) > 0;
        })->values();
    }
}