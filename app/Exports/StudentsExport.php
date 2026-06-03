<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Collection;

class StudentsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $students;

    public function __construct(Collection $students)
    {
        $this->students = $students;
    }

    public function collection() { return $this->students; }

    public function headings(): array
    {
        return ['Nom','CIN','Téléphone','Email','Formation','Date début',
                'Engagement','Payé','Reste','Ville','Groupe'];
    }

    public function map($s): array
    {
        return [
            $s->name, $s->cin, $s->phone, $s->email,
            optional($s->formation)->name,
            optional($s->start_date)->format('Y-m-d'),
            $s->engagement, $s->payment_done, $s->payment_remaining,
            $s->city,
            $s->groups->pluck('name')->implode(', '),
        ];
    }
}
