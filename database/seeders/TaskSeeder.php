<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Task::insert([
        //     [
        //         "assigned_to" => 2,
        //         "start_date" => "23 jul 2024",
        //         "end_date" => "23 aug 2024",
        //         "priority" => "High",
        //         "status" => "Active"
        //     ],
        //     [
        //         "assigned_to" => 2,
        //         "start_date" => "24 jul 2024",
        //         "end_date" => "23 aug 2024",
        //         "priority" => "Medium",
        //         "status" => "Active"
        //     ],
        //     [
        //         "assigned_to" => 2,
        //         "start_date" => "25 jul 2024",
        //         "end_date" => "23 aug 2024",
        //         "priority" => "Lower",
        //         "status" => "Active"
        //     ],
        // ]);
    }
}
