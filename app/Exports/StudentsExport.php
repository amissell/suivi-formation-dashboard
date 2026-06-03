<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Database\Eloquent\Builder;

class StudentsExport implements FromQuery, WithHeadings, WithMapping, WithChunkReading
{
    protected Builder $query;

    public function __construct(Builder $query)
    {
        // Force eager loading of relations used in map()
        $this->query = $query->with(['formation', 'groups']);
    }

    public function query()
    {
        return $this->query;
    }

    public function headings(): array
    {
        return [
            'Nom', 'CIN', 'Téléphone', 'Email', 'Formation', 'Date début',
            'Engagement', 'Payé', 'Reste', 'Ville', 'Groupes',
        ];
    }

    public function map($s): array
    {
        return [
            $s->name,
            $s->cin,
            $s->phone,
            $s->email,
            optional($s->formation)->name,
            optional($s->start_date)->format('Y-m-d'),
            $s->engagement,
            $s->payment_done,
            $s->payment_remaining,
            $s->city,
            $s->groups->pluck('name')->implode(', '),
        ];
    }

    public function chunkSize(): int
    {
        return 500;
    }
}
