<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Formation extends Model
{
    protected $fillable = ['name', 'trainer', 'price'];


public function students()
{
    return $this->hasMany(Student::class);
}


}
