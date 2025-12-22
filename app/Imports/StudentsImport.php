<?php

namespace App\Imports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Student([
            'name'          => $row['name'],
            'cin'           => $row['cin'],
            'phone'         => $row['phone'],
            'email'         => $row['email'] ?? null,
            'formation_id'  => $row['formation_id'],
            'start_date'    => $row['start_date'],
            'engagement'    => $row['engagement'],
        ]);
    }
}
