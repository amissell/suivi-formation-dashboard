<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = ['name', 'month_date', 'formation_id', 'notes'];

    protected $casts = [
        'month_date' => 'date',
    ];

    public function students()
    {
        return $this->belongsToMany(Student::class)->withTimestamps();
    }

    public function formation()
    {
        return $this->belongsTo(Formation::class);
    }
}
