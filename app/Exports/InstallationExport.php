<?php

namespace App\Exports;

use App\Models\Installation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class InstallationExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Installation::with(['project', 'manager', 'assignee'])->where('archive', false)->get();
    }


    public function headings(): array
    {
        return [
            'ID',
            'Project',
            'Manager',
            'Assigned To',
            'Installation Amount',
            'Shipment Amount',
            'Start Date',
            'End Date',
            'Status',
            'Notes'
        ];
    }


    public function map($installation): array
    {
        return [
            $installation->id,
            $installation->project->project_name,
            $installation->manager->name,
            $installation->assignee->name,
            $installation->installation_amount,
            $installation->shipment_amount,
            $installation->start_date->format("d M Y"),
            $installation->end_date->format("d M Y"),
            $installation->status,
            $installation->notes
        ];
    }
}
