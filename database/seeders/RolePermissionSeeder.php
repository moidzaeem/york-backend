<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Role::create(['name' => 'Administrator']);
        Role::create(['name' => 'CEO']);
        Role::create(['name' => 'Director']);
        Role::create(['name' => 'Finance Manager']);
        Role::create(['name' => 'Project Manager']);
        Role::create(['name' => 'HR Manager']);
        Role::create(['name' => 'Accountant']);
        Role::create(['name' => 'User']);


        // Permissions

        Permission::create(['name' => 'Tasks - View']);
        Permission::create(['name' => 'Tasks - Create']);
        Permission::create(['name' => 'Tasks - Edit']);
        Permission::create(['name' => 'Tasks - Delete']);

        Permission::create(['name' => 'Tenders - View']);
        Permission::create(['name' => 'Tenders - Create']);
        Permission::create(['name' => 'Tenders - Edit']);
        Permission::create(['name' => 'Tenders - Delete']);

        Permission::create(['name' => 'Projects - View']);
        Permission::create(['name' => 'Projects - Create']);
        Permission::create(['name' => 'Projects - Edit']);
        Permission::create(['name' => 'Projects - Delete']);

        Permission::create(['name' => 'Clients - View']);
        Permission::create(['name' => 'Clients - Create']);
        Permission::create(['name' => 'Clients - Edit']);
        Permission::create(['name' => 'Clients - Delete']);

        Permission::create(['name' => 'Sales - View']);
        Permission::create(['name' => 'Sales - Create']);
        Permission::create(['name' => 'Sales - Edit']);
        Permission::create(['name' => 'Sales - Delete']);

        Permission::create(['name' => 'Installations - View']);
        Permission::create(['name' => 'Installations - Create']);
        Permission::create(['name' => 'Installations - Edit']);
        Permission::create(['name' => 'Installations - Delete']);

        Permission::create(['name' => 'ChangeOrders - View']);
        Permission::create(['name' => 'ChangeOrders - Create']);
        Permission::create(['name' => 'ChangeOrders - Edit']);
        Permission::create(['name' => 'ChangeOrders - Delete']);

        Permission::create(['name' => 'BackCharges - View']);
        Permission::create(['name' => 'BackCharges - Create']);
        Permission::create(['name' => 'BackCharges - Edit']);
        Permission::create(['name' => 'BackCharges - Delete']);

        Permission::create(['name' => 'Punch Lists - View']);
        Permission::create(['name' => 'Punch Lists - Create']);
        Permission::create(['name' => 'Punch Lists - Edit']);
        Permission::create(['name' => 'Punch Lists - Delete']);

        Permission::create(['name' => 'Logistics - View']);
        Permission::create(['name' => 'Logistics - Create']);
        Permission::create(['name' => 'Logistics - Edit']);
        Permission::create(['name' => 'Logistics - Delete']);

        Permission::create(['name' => 'Education - View']);
        Permission::create(['name' => 'Education - Create']);
        Permission::create(['name' => 'Education - Edit']);
        Permission::create(['name' => 'Education - Delete']);

        Permission::create(['name' => 'Tickets - View']);
        Permission::create(['name' => 'Tickets - Create']);
        Permission::create(['name' => 'Tickets - Edit']);
        Permission::create(['name' => 'Tickets - Delete']);

        Permission::create(['name' => 'Inputs - View']);
        Permission::create(['name' => 'Inputs - Create']);
        Permission::create(['name' => 'Inputs - Edit']);
        Permission::create(['name' => 'Inputs - Delete']);

        Permission::create(['name' => 'Categories - View']);
        Permission::create(['name' => 'Categories - Create']);
        Permission::create(['name' => 'Categories - Edit']);
        Permission::create(['name' => 'Categories - Delete']);

        Permission::create(['name' => 'Departments - View']);
        Permission::create(['name' => 'Departments - Create']);
        Permission::create(['name' => 'Departments - Edit']);
        Permission::create(['name' => 'Departments - Delete']);

        Permission::create(['name' => 'Employees - View']);
        Permission::create(['name' => 'Employees - Create']);
        Permission::create(['name' => 'Employees - Edit']);
        Permission::create(['name' => 'Employees - Delete']);

        Permission::create(['name' => 'Users - View']);
        Permission::create(['name' => 'Users - Change Status']);
        Permission::create(['name' => 'Users - Change Role']);

        Permission::create(['name' => 'Calendar']);
        Permission::create(['name' => 'Events - Create']);

        Permission::create(['name' => 'General Settings']);
        Permission::create(['name' => 'Converter']);
        Permission::create(['name' => 'Files & Folders']);
        Permission::create(['name' => 'Comments Popup']);
        Permission::create(['name' => 'Photos Popup']);
        Permission::create(['name' => 'Logs - View']);
        Permission::create(['name' => 'Logs - Delete']);
    }
}
