<?php

namespace App\Http\Controllers;

use App\Exports\ClientExport;
use App\Exports\InstallationExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProjectExport;
use App\Exports\SaleExport;
use App\Exports\TaskExport;
use App\Exports\TenderExport;
use Exception;
use Illuminate\Http\Request;

class ExportController extends Controller
{

    public function exportResource(Request $rq, $slug)
    {
        try {
            switch ($slug) {
                case 'tasks':
                    activity()
                        ->log(':causer.name downloaded tasks');
                    return Excel::download(new TaskExport, 'tasks.xlsx');
                    break;
                case 'tenders':
                    activity()
                        ->log(':causer.name downloaded tenders');
                    return Excel::download(new TenderExport, 'tenders.xlsx');
                    break;
                case 'projects':
                    activity()
                        ->log(':causer.name downloaded projects');
                    return Excel::download(new ProjectExport, 'projects.xlsx');
                    break;
                case 'sales':
                    activity()
                        ->log(':causer.name downloaded sales');
                    return Excel::download(new SaleExport, 'sales.xlsx');
                    break;
                case 'installations':
                    activity()
                        ->log(':causer.name downloaded installations');
                    return Excel::download(new InstallationExport, 'installations.xlsx');
                    break;
                case 'clients':
                    activity()
                        ->log(':causer.name downloaded clients');
                    return Excel::download(new ClientExport, 'clients.xlsx');
                    break;

                default:
                    throw new Exception("Unknow export detected");
                    break;
            }
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 404,
                "message" => $th?->getMessage()
            ]);
        }
    }
}
