<?php

namespace App\Exports;

use App\Models\Sale;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SaleExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Sale::with(['client', 'salesPerson', 'category'])->where('archive', false)->get();
    }


    public function headings(): array
    {
        return [
            'ID',
            'Client',
            'Sales Person',
            'Category',
            'Offer Price',
            'Date',
            'Status',
            'Notes'
        ];
    }


    public function map($tender): array
    {
        return [
            $tender->id,
            $tender->client->name,
            $tender->salesPerson->name,
            $tender->category->title,
            $tender->offer_price,
            $tender->date->format("d M Y"),
            $tender->status,
            $tender->notes
        ];
    }
}
