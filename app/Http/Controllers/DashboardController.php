<?php

namespace App\Http\Controllers;

use App\Http\Resources\BirthdayResource;
use App\Http\Resources\TodayTaskResource;
use App\Models\Client;
use App\Models\Task;
use App\Models\Tender;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    protected $year;
    protected $month;
    protected $day;

    public function __construct()
    {
        $this->year = date("Y");
        $this->month = date("m");
        $this->day = date("d");
    }

    public function index(Request $rq)
    {

        if ($rq->input("month")) {
            $this->month = $rq->input("month");
        }

        return response()->json([
            "total_tasks" => Task::count(),
            "total_clients" => Client::count(),
            "total_tenders" => Tender::count(),
            "birthdays" => $this->getBirthDays(),
            "today_tasks" => $this->getTodayTasks(),
            "weather" => [
                "brantford" => [
                    "title" => "Brantford",
                    "label" => "Sunny Day",
                    "temp" => 24,
                ],
                "north_york" => [
                    "title" => "North York",
                    "label" => "Cloudy",
                    "temp" => 18,
                ],
            ]

        ]);
    }


    protected function getBirthDays()
    {
        $query = User::query()
            ->whereMonth("dob", $this->month)
            ->where("is_client", false)
            ->orderBy('dob', 'asc')
            ->get();

        // other conditions

        $result = BirthdayResource::collection($query);

        return [
            "total" => count($result),
            "result" => $result
        ];
    }


    protected function getTodayTasks()
    {
        $query = Task::query()
            ->where("archive", 0)
            ->whereYear("end_date", $this->year)
            ->whereMonth("end_date", $this->month);
        // ->whereMonth("end_date", 02);
        // ->whereDay("end_date", $this->day);

        // other conditions

        return TodayTaskResource::collection($query->get());
    }
}
