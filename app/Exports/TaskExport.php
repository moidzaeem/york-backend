<?php

namespace App\Exports;

use App\Models\Task;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TaskExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Task::with(['client', 'category', 'assignee', 'department'])->where('archive', false)->get();
    }


    public function headings(): array
    {
        return [
            'ID',
            'Title',
            'Client',
            'Category',
            'Assigned To',
            'Department',
            'Start Date',
            'End Date',
            'Status',
            'Priority',
            'Notes'
        ];
    }


    public function map($tender): array
    {
        return [
            $tender->id,
            $tender->title,
            $tender->client->name,
            $tender->category->title,
            $tender->assignee->name,
            $tender->department->name,
            $tender->start_date->format("d M Y"),
            $tender->end_date->format("d M Y"),
            $tender->status,
            $tender->priority,
            $tender->notes
        ];
    }
}
