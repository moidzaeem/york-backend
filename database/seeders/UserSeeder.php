<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            "name" => "Jawad khan",
            "phone_number" => "03420909974",
            "email" => "jawadk116@gmail.com",
            "password" => "$2y$12$3HQdErzzDI5bNHmdIF0KlOqOMrxfXvDtzwND.zDFOTWCgPxDjgEsW",
            "dob" => Carbon::createFromFormat("Y-m-d", "1997-04-28"),
            "is_client" => 0,
            "status" => "Approved",
            "avatar" => "uploads/avatars/user1.jpg",
        ]);

        $permissions = Permission::all()->pluck("name")->toArray();

        $user->syncPermissions($permissions);

        // User::create([
        //     "name" => "Majid Ullah",
        //     "phone_number" => "03101626464",
        //     "email" => "majid.engr@gmail.com",
        //     "password" => "$2y$12$3HQdErzzDI5bNHmdIF0KlOqOMrxfXvDtzwND.zDFOTWCgPxDjgEsW",
        //     "is_client" => 0,
        //     "avatar" => "uploads/avatars/user2.jpg",
        // ]);
    }
}
