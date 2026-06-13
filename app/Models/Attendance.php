<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'seance_id', 'student_id', 'status',
    ];

    const STATUSES = [
        'present' => 'Présent',
        'absent'  => 'Absent',
        'late'    => 'Retard',
    ];

    public function seance()
    {
        return $this->belongsTo(Seance::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}