<?php

namespace App\Exports;

use App\Models\Client;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ClientExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Client::with('profile')->get();
    }


    public function headings(): array
    {
        return [
            'ID',
            'Client Name',
            'Company Name',
            'Phone (Cell)',
            'Phone (Office)',
            'Email',
            'Address',
            'Special Client',
            'Blacklisted',
            'Status'
        ];
    }


    public function map($client): array
    {
        return [
            $client->id,
            $client->profile->name,
            $client->company_name,
            $client->phone_gsm,
            $client->phone_stable,
            $client->profile->email,
            $client->profile->address,
            $client->profile->special ? "true" : "false",
            $client->profile->blacklist ? "true" : "false",
            $client->status
        ];
    }
}
