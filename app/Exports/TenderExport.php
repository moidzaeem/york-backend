<?php

namespace App\Exports;

use App\Models\Tender;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TenderExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Tender::with(['client', 'category'])->where('archive', false)->get();
    }


    public function headings(): array
    {
        return [
            'ID',
            'Project Name',
            'Client',
            'Category',
            'Closing Date',
            'Status',
            'Notes'
        ];
    }


    public function map($tender): array
    {
        return [
            $tender->id,
            $tender->project_name,
            $tender->client->name,
            $tender->category->title,
            $tender->closing_date->format("d M Y"),
            $tender->status,
            $tender->notes
        ];
    }
}
