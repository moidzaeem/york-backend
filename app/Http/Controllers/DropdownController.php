<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Client;
use App\Models\Department;
use App\Models\Position;
use App\Models\Project;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DropdownController extends Controller
{


    public function tenderDropdowns()
    {
        return response()->json([
            "statuses" => $this->tenderStatuses(),
            // "clients" => Client::with("profile")->select(["profile.id", "company_name", "profile.name"])->get()->toArray(),
            "clients" => User::select(["id", "name"])->where("is_client", 1)->get()->toArray(),
            "categories" => Category::select(["id", "title"])->get()->toArray(),
        ]);
    }

    public function projectDropdowns()
    {
        return response()->json([
            "statuses" => $this->projectStatuses(),
            // "clients" => Client::with("profile")->select(["profile.id", "company_name", "profile.name"])->get()->toArray(),
            "clients" => User::select(["id", "name"])->where("is_client", 1)->get()->toArray(),
            "categories" => Category::select(["id", "title"])->get()->toArray(),
            "users" => User::select(["id", "name"])->where("is_client", "<>", 1)->get()->toArray(),
        ]);
    }

    public function clientDropdowns()
    {
        return response()->json([
            "statuses" => $this->clientStatuses(),
            "roles" => $this->userRoles()
        ]);
    }

    public function taskDropdowns()
    {
        return response()->json([
            "statuses" => $this->taskStatuses(),
            "priorities" => $this->taskPriorities(),
            // "clients" => Client::with("profile")->select(["profile.id", "company_name", "profile.name"])->get()->toArray(),
            "clients" => User::select(["id", "name"])->where("is_client", 1)->get()->toArray(),
            "categories" => Category::select(["id", "title"])->get()->toArray(),
            "departments" => Department::select(["id", "name"])->get()->toArray(),
            "users" => User::select(["id", "name"])->where("is_client", "<>", 1)->get()->toArray(),
        ]);
    }

    public function eventDropdowns()
    {
        return response()->json([
            "statuses" => $this->eventStatuses(),
            // "clients" => Client::with("profile")->select(["profile.id", "company_name", "profile.name"])->get()->toArray(),
            "clients" => User::select(["id", "name"])->where("is_client", 1)->get()->toArray(),
            "departments" => Department::select(["id", "name"])->get()->toArray(),
            "users" => User::select(["id", "name"])->where("is_client", "<>", 1)->get()->toArray(),
        ]);
    }

    public function saleDropdowns()
    {
        return response()->json([
            "statuses" => $this->saleStatuses(),
            // "clients" => Client::with("profile")->select(["profile.id", "company_name", "profile.name"])->get()->toArray(),
            "clients" => User::select(["id", "name"])->where("is_client", 1)->get()->toArray(),
            "categories" => Category::select(["id", "title"])->get()->toArray(),
            "users" => User::select(["id", "name"])->where("is_client", "<>", 1)->get()->toArray(),
        ]);
    }

    public function installationDropdowns()
    {
        return response()->json([
            "statuses" => $this->installationStatuses(),
            // "clients" => Client::with("profile")->select(["profile.id", "company_name", "profile.name"])->get()->toArray(),
            // "clients" => User::select(["id", "name"])->where("is_client", 1)->get()->toArray(),
            // "categories" => Category::select(["id", "title"])->get()->toArray(),
            "projects" => Project::select(["id", "project_name"])->get()->toArray(),
            "users" => User::select(["id", "name"])->where("is_client", "<>", 1)->get()->toArray(),
        ]);
    }

    public function changeOrderDropdowns()
    {
        return response()->json([
            // "clients" => Client::with("profile")->select(["profile.id", "company_name", "profile.name"])->get()->toArray(),
            "clients" => User::select(["id", "name"])->where("is_client", 1)->get()->toArray(),
            "projects" => Project::select(["id", "project_name"])->get()->toArray(),
        ]);
    }

    public function backChargeDropdowns()
    {
        return response()->json([
            // "clients" => Client::with("profile")->select(["profile.id", "company_name", "profile.name"])->get()->toArray(),
            "clients" => User::select(["id", "name"])->where("is_client", 1)->get()->toArray(),
            "projects" => Project::select(["id", "project_name"])->get()->toArray(),
        ]);
    }

    public function punchListDropdowns()
    {
        return response()->json([
            "statuses" => $this->punchlistStatuses(),
            "priorities" => $this->punchlistPriorities(),
            // "clients" => Client::with("profile")->select(["profile.id", "company_name", "profile.name"])->get()->toArray(),
            // "clients" => User::select(["id", "name"])->where("is_client", 1)->get()->toArray(),
            "projects" => Project::select(["id", "project_name"])->get()->toArray(),
            "users" => User::select(["id", "name"])->where("is_client", "<>", 1)->get()->toArray(),
        ]);
    }

    public function employeeDropdowns()
    {
        return response()->json([
            "departments" => Department::select(["id", "name"])->get()->toArray(),
            "positions" => Position::select("id", "title")->get()->toArray(),
            "employment_types" => $this->employmentTypes(),
            "roles" => $this->userRoles(),
            "permissions" => $this->userPermissions(),
        ]);
    }

    public function logisticDropdowns()
    {
        return response()->json([
            "statuses" => $this->logisticStatuses(),
            // "clients" => Client::with("profile")->select(["profile.id", "company_name", "profile.name"])->get()->toArray(),
            "clients" => User::select(["id", "name"])->where("is_client", 1)->get()->toArray(),
            "projects" => Project::select(["id", "project_name"])->get()->toArray(),
            "users" => User::select(["id", "name"])->where("is_client", "<>", 1)->get()->toArray(),
        ]);
    }

    public function learningMaterialDropdowns()
    {
        return response()->json([
            "categories" => Category::select(["id", "title"])->get()->toArray(),
        ]);
    }

    public function ticketDropdowns()
    {
        return response()->json([
            "categories" => Category::select(["id", "title"])->get()->toArray(),
            "statuses" => $this->ticketStatuses()
        ]);
    }

    public function inputDropdowns()
    {
        return response()->json([
            "categories" => Category::select(["id", "title"])->get()->toArray(),
        ]);
    }

    public function userDropdowns()
    {
        return response()->json([
            "statuses" => $this->userStatuses(),
            "roles" => Role::all()->pluck('name')
        ]);
    }

    public function searchDropdowns()
    {
        return response()->json([
            "types" => [
                "User",
                // "Employee",
                // "Client",
                // "Tender",
                // "Project",
                // "Task",
                // "Ticket",
                // "PunchList",
                // "ChangeOrder",
                // "BackCharge",
            ]
        ]);
    }


    /**
     * 
     * 
     * 
     * 
     * **************************************************************************************************
     * **************************************************************************************************
     * ********************************** Dropdowns protected methods ***********************************
     * **************************************************************************************************
     * **************************************************************************************************
     * 
     * 
     * 
     * 
     */

    protected function taskStatuses()
    {
        return [
            "0%",
            "10%",
            "20%",
            "30%",
            "40%",
            "50%",
            "60%",
            "70%",
            "80%",
            "90%",
            "100%",
        ];
    }

    protected function taskPriorities()
    {
        return [
            "High",
            "Medium",
            "Normal",
            "Low"
        ];
    }

    protected function tenderStatuses()
    {
        return [
            "Offer Made",
            "Pending",
            "Won",
            "Lost"
        ];
    }

    protected function projectStatuses()
    {
        return [
            "Pending",
            "Canceled",
            "Completed",
            "Postponed",
            "Ongoing"
        ];
    }

    protected function clientStatuses()
    {
        return [
            "Pending",
            "Approved",
            "Rejected",
            "Banned"
        ];
    }

    protected function userRoles()
    {

        return Role::select(["id", "name"])->orderBy("name", "asc")->get();
    }

    protected function userPermissions()
    {

        return Permission::select(["id", "name"])->orderBy("name", "asc")->get();
    }

    protected function ticketStatuses()
    {
        return [
            "New",
            "Solved",
            "Accepted",
            "Not Accepted",
            "Delayed"
        ];
    }

    protected function saleStatuses()
    {
        return [
            "Bid Given",
            "Bid Not Given",
            "Accepted",
            "Rejected"
        ];
    }

    protected function punchlistStatuses()
    {
        return [
            "Pending",
            "Canceled",
            "Completed",
            "Postponed",
            "Ongoing"
        ];
    }

    protected function punchlistPriorities()
    {
        return [
            "High",
            "Medium",
            "Normal",
            "Low"
        ];
    }

    protected function employmentTypes()
    {
        return [
            "Full Time",
            "Part Time",
            "Contract",
            "Remote",
            "Seasonal"
        ];
    }

    protected function logisticStatuses()
    {
        return [
            "Pending",
            "In Shipment",
            "Completed",
            "Delayed",
            "Canceled"
        ];
    }

    protected function installationStatuses()
    {
        return [
            "Pending",
            "Canceled",
            "Completed",
            "Postponed",
            "Ongoing"
        ];
    }

    protected function eventStatuses()
    {
        return [
            "Pending",
            "Completed",
            "Canceled",
            "Postponed"
        ];
    }

    protected function userStatuses()
    {
        return [
            "Pending",
            "Approved",
            "Rejected",
            "Banned"
        ];
    }
}
