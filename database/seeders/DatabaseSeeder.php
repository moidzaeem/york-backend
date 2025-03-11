<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Department;
use App\Models\Position;
use App\Models\FileSystem;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call(RolePermissionSeeder::class);

        $this->call(UserSeeder::class);

        Department::create([
            "title" => "dept 1",
            "name" => "Department one",
        ]);
        Department::create([
            "title" => "dept 2",
            "name" => "Department two",
        ]);
        Department::create([
            "title" => "dept 3",
            "name" => "Department three",
        ]);
        Department::create([
            "title" => "dept 4",
            "name" => "Department four",
        ]);
        Department::create([
            "title" => "dept 5",
            "name" => "Department five",
        ]);


        Category::create([
            "title" => "Category One",
        ]);

        Category::create([
            "title" => "Category Two",
        ]);

        Category::create([
            "title" => "Category Three",
        ]);

        Category::create([
            "title" => "Category Four",
        ]);

        Category::create([
            "title" => "Category Five",
        ]);

        Category::create([
            "title" => "Category Six",
        ]);

        Position::create([
            "title" => "Manager",
        ]);

        Position::create([
            "title" => "Accountant",
        ]);

        Position::create([
            "title" => "Finance Manager",
        ]);

        Position::create([
            "title" => "Driver",
        ]);

        Position::create([
            "title" => "Designer",
        ]);

        Position::create([
            "title" => "Software Engineer",
        ]);

        // Department::factory(50)->create();

        // $this->call(TaskSeeder::class);
    }
}
