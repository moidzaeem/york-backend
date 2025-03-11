<?php

namespace App\Exports;

use App\Models\Project;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProjectExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Project::with(['client', 'manager', 'category'])->where('archive', false)->get();
    }


    public function headings(): array
    {
        return [
            '#',
            'Project Name',
            'Client',
            'Manager',
            'Category',
            'Start Date',
            'End Date',
            'Status',
            'Notes'
        ];
    }


    public function map($project): array
    {
        return [
            $project->id,
            $project->project_name,
            $project->client->name,
            $project->manager->name,
            $project->category->title,
            $project->start_date->format("d M Y"),
            $project->end_date->format("d M Y"),
            $project->status,
            $project->notes
        ];
    }
}
