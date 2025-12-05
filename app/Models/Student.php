<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'cin',
        'phone',
        'email',
        'formation_id',
        'start_date',
        'payment_done',
        'payment_remaining',
        'attestation',
        'status',
        'city',
        'notes',
    ];

    protected $dates = [
        'start_date' => 'date',
        'payment_done' => 'decimal:2',
        'payment_remaining' => 'decimal:2',
    ];

    public function formation()
    {
        return $this->belongsTo(Formation::class);
    }
}
