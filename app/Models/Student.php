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
        'attestation', 'city', 'notes',
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
}
