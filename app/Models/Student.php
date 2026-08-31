<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'cin', 'phone', 'email', 'formation_id',
        'start_date', 'engagement', 'payment_done', 'payment_remaining',
        'attestation', 'certificate_generated', 'city', 'notes',
    ];

    protected $casts = [
        'start_date'        => 'date',
        'engagement'        => 'decimal:2',
        'payment_done'      => 'decimal:2',
        'payment_remaining' => 'decimal:2',
    ];

    public function formation()
    {
        return $this->belongsTo(Formation::class);
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class)->withTimestamps();
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Reusable date-range filter.
     * Usage: Student::dateRange($from, $to)->get();
     */
    public function scopeDateRange($query, ?string $from, ?string $to, string $column = 'created_at')
    {
        if ($from) {
            $query->whereDate($column, '>=', $from);
        }
        if ($to) {
            $query->whereDate($column, '<=', $to);
        }
        return $query;
    }

    /**
     * Dynamically computed engagement (attendance) rate, 0-100.
     * Based on Attendance records for "réalisée" sessions only.
     * present = 1, late = 0.5, absent = 0.
     * Returns null if the student has no recorded sessions yet.
     */
    public function getEngagementRateAttribute(): ?float
    {
        $records = $this->attendances()
            ->whereHas('seance', function ($q) {
                $q->where('statut', 'réalisée');
            })
            ->get(['status']);

        if ($records->isEmpty()) {
            return null;
        }

        $total = $records->count();
        $score = $records->sum(function ($a) {
            return match ($a->status) {
                'present' => 1,
                'late'    => 0.5,
                default   => 0,
            };
        });

        return round(($score / $total) * 100, 1);
    }

    /**
     * Counts for a quick badge: [present, absent, late, total]
     */
    public function getAttendanceSummaryAttribute(): array
    {
        $records = $this->attendances()
            ->whereHas('seance', function ($q) {
                $q->where('statut', 'réalisée');
            })
            ->get(['status']);

        return [
            'present' => $records->where('status', 'present')->count(),
            'absent'  => $records->where('status', 'absent')->count(),
            'late'    => $records->where('status', 'late')->count(),
            'total'   => $records->count(),
        ];
    }
}
