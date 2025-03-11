<?php

use App\Http\Controllers\BackChargeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChangeOrderController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DropdownController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\FileSystemController;
use App\Http\Controllers\InputController;
use App\Http\Controllers\InstallationController;
use App\Http\Controllers\LearningMaterialController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\LogisticController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\PunchListController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TenderController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::get("/user", [UserController::class, "loggedinUser"]);
    Route::get("/search", [SearchController::class, "index"]);
    Route::get("/notifications", [NotificationController::class, "index"]);

    Route::prefix("dashboard")->group(function () {
        Route::get("/", [DashboardController::class, "index"]);
    });

    Route::get("/files", [FileSystemController::class, "list"]);
    Route::post('/directory', [FileSystemController::class, 'createDirectory']);
    Route::post('/upload', [FileSystemController::class, 'uploadFile']);
    Route::get('/file/{slug}', [FileSystemController::class, 'getFile'])->name('file.get');
    Route::post("/filesystem/delete", [FileSystemController::class, "delete"]);

    Route::prefix("users")->group(function () {
        Route::get("/", [UserController::class, "list"]);
        Route::post("/create", [UserController::class, "save"]);
        Route::get("/{id}", [UserController::class, "edit"]);
        Route::post("/update", [UserController::class, "update"]);
        Route::post("/delete", [UserController::class, "delete"]);
        Route::post("/change_status", [UserController::class, "changeStatus"]);
        Route::post("/switch_role", [UserController::class, "switchRole"]);
    });

    Route::prefix("employees")->group(function () {
        Route::get("/", [EmployeeController::class, "list"]);
        Route::post("/create", [EmployeeController::class, "save"]);
        Route::get("/{id}", [EmployeeController::class, "edit"]);
        Route::post("/update", [EmployeeController::class, "update"]);
        Route::post("/delete", [EmployeeController::class, "delete"]);
        Route::post("/archive", [EmployeeController::class, "archive"]);
    });

    Route::prefix("clients")->group(function () {
        Route::get("/", [ClientController::class, "list"]);
        Route::post("/create", [ClientController::class, "save"]);
        Route::get("/{id}", [ClientController::class, "edit"]);
        Route::post("/update", [ClientController::class, "update"]);
        Route::post("/delete", [ClientController::class, "delete"]);
        Route::post("/archive", [ClientController::class, "archive"]);
    });

    Route::prefix("tenders")->group(function () {
        Route::get("/", [TenderController::class, "list"]);
        Route::post("/create", [TenderController::class, "save"]);
        Route::get("/{id}", [TenderController::class, "edit"]);
        Route::post("/update", [TenderController::class, "update"]);
        Route::post("/delete", [TenderController::class, "delete"]);
        Route::post("/archive", [TenderController::class, "archive"]);
        Route::post("/change_status", [TenderController::class, "changeStatus"]);
        Route::get("/comments/{id}", [TenderController::class, "comments"]);
        Route::post("/comment", [TenderController::class, "saveComment"]);
        Route::get("/files/{id}", [TenderController::class, "files"]);
        Route::post("/file", [TenderController::class, "saveFile"]);
    });

    Route::prefix("projects")->group(function () {
        Route::get("/", [ProjectController::class, "list"]);
        Route::post("/create", [ProjectController::class, "save"]);
        Route::get("/{id}", [ProjectController::class, "edit"]);
        Route::post("/update", [ProjectController::class, "update"]);
        Route::post("/delete", [ProjectController::class, "delete"]);
        Route::post("/archive", [ProjectController::class, "archive"]);
        Route::post("/change_status", [ProjectController::class, "changeStatus"]);
        Route::get("/comments/{id}", [ProjectController::class, "comments"]);
        Route::post("/comment", [ProjectController::class, "saveComment"]);
        Route::get("/files/{id}", [ProjectController::class, "files"]);
        Route::post("/file", [ProjectController::class, "saveFile"]);
    });

    Route::prefix("tasks")->group(function () {
        Route::get("/", [TaskController::class, "list"]);
        Route::post("/create", [TaskController::class, "save"]);
        Route::get("/{id}", [TaskController::class, "edit"]);
        Route::post("/update", [TaskController::class, "update"]);
        Route::post("/delete", [TaskController::class, "delete"]);
        Route::post("/archive", [TaskController::class, "archive"]);
        Route::post("/change_status", [TaskController::class, "changeStatus"]);
        Route::get("/comments/{id}", [TaskController::class, "comments"]);
        Route::post("/comment", [TaskController::class, "saveComment"]);
        Route::get("/files/{id}", [TaskController::class, "files"]);
        Route::post("/file", [TaskController::class, "saveFile"]);
    });

    Route::prefix("events")->group(function () {
        Route::get("/", [EventController::class, "list"]);
        Route::get("/calendar/{month}", [EventController::class, "monthlyEvents"]);
        Route::post("/create", [EventController::class, "save"]);
        Route::get("/{id}", [EventController::class, "edit"]);
        Route::post("/update", [EventController::class, "update"]);
        Route::post("/delete", [EventController::class, "delete"]);
        Route::post("/archive", [EventController::class, "archive"]);
        Route::post("/change_status", [EventController::class, "changeStatus"]);
        Route::get("/comments/{id}", [EventController::class, "comments"]);
        Route::post("/comment", [EventController::class, "saveComment"]);
        Route::get("/files/{id}", [EventController::class, "files"]);
        Route::post("/file", [EventController::class, "saveFile"]);
    });

    Route::prefix("sales")->group(function () {
        Route::get("/", [SaleController::class, "list"]);
        Route::post("/create", [SaleController::class, "save"]);
        Route::get("/{id}", [SaleController::class, "edit"]);
        Route::post("/update", [SaleController::class, "update"]);
        Route::post("/delete", [SaleController::class, "delete"]);
        Route::post("/archive", [SaleController::class, "archive"]);
        Route::post("/change_status", [SaleController::class, "changeStatus"]);
        Route::get("/comments/{id}", [SaleController::class, "comments"]);
        Route::post("/comment", [SaleController::class, "saveComment"]);
        Route::get("/files/{id}", [SaleController::class, "files"]);
        Route::post("/file", [SaleController::class, "saveFile"]);
    });

    Route::prefix("installations")->group(function () {
        Route::get("/", [InstallationController::class, "list"]);
        Route::post("/create", [InstallationController::class, "save"]);
        Route::get("/{id}", [InstallationController::class, "edit"]);
        Route::post("/update", [InstallationController::class, "update"]);
        Route::post("/delete", [InstallationController::class, "delete"]);
        Route::post("/archive", [InstallationController::class, "archive"]);
        Route::post("/change_status", [InstallationController::class, "changeStatus"]);
        Route::get("/comments/{id}", [InstallationController::class, "comments"]);
        Route::post("/comment", [InstallationController::class, "saveComment"]);
        Route::get("/files/{id}", [InstallationController::class, "files"]);
        Route::post("/file", [InstallationController::class, "saveFile"]);
    });

    Route::prefix("change_order")->group(function () {
        Route::get("/", [ChangeOrderController::class, "list"]);
        Route::post("/create", [ChangeOrderController::class, "save"]);
        Route::get("/{id}", [ChangeOrderController::class, "edit"]);
        Route::post("/update", [ChangeOrderController::class, "update"]);
        Route::post("/delete", [ChangeOrderController::class, "delete"]);
        Route::post("/archive", [ChangeOrderController::class, "archive"]);
        Route::get("/comments/{id}", [ChangeOrderController::class, "comments"]);
        Route::post("/comment", [ChangeOrderController::class, "saveComment"]);
    });

    Route::prefix("back_charge")->group(function () {
        Route::get("/", [BackChargeController::class, "list"]);
        Route::post("/create", [BackChargeController::class, "save"]);
        Route::get("/{id}", [BackChargeController::class, "edit"]);
        Route::post("/update", [BackChargeController::class, "update"]);
        Route::post("/delete", [BackChargeController::class, "delete"]);
        Route::post("/archive", [BackChargeController::class, "archive"]);
        Route::get("/comments/{id}", [BackChargeController::class, "comments"]);
        Route::post("/comment", [BackChargeController::class, "saveComment"]);
        Route::get("/files/{id}", [BackChargeController::class, "files"]);
        Route::post("/file", [BackChargeController::class, "saveFile"]);
    });

    Route::prefix("punch_list")->group(function () {
        Route::get("/", [PunchListController::class, "list"]);
        Route::post("/create", [PunchListController::class, "save"]);
        Route::get("/{id}", [PunchListController::class, "edit"]);
        Route::post("/update", [PunchListController::class, "update"]);
        Route::post("/delete", [PunchListController::class, "delete"]);
        Route::post("/archive", [PunchListController::class, "archive"]);
        Route::post("/change_status", [PunchListController::class, "changeStatus"]);
        Route::get("/comments/{id}", [PunchListController::class, "comments"]);
        Route::post("/comment", [PunchListController::class, "saveComment"]);
        Route::get("/files/{id}", [PunchListController::class, "files"]);
        Route::post("/file", [PunchListController::class, "saveFile"]);
    });

    Route::prefix("logistics")->group(function () {
        Route::get("/", [LogisticController::class, "list"]);
        Route::post("/create", [LogisticController::class, "save"]);
        Route::get("/{id}", [LogisticController::class, "edit"]);
        Route::post("/update", [LogisticController::class, "update"]);
        Route::post("/delete", [LogisticController::class, "delete"]);
        Route::post("/archive", [LogisticController::class, "archive"]);
        Route::post("/change_status", [LogisticController::class, "changeStatus"]);
        Route::get("/comments/{id}", [LogisticController::class, "comments"]);
        Route::post("/comment", [LogisticController::class, "saveComment"]);
        Route::get("/files/{id}", [LogisticController::class, "files"]);
        Route::post("/file", [LogisticController::class, "saveFile"]);
    });

    Route::prefix("learning_materials")->group(function () {
        Route::get("/", [LearningMaterialController::class, "list"]);
        Route::post("/create", [LearningMaterialController::class, "save"]);
        Route::get("/{id}", [LearningMaterialController::class, "edit"]);
        Route::post("/update", [LearningMaterialController::class, "update"]);
        Route::post("/delete", [LearningMaterialController::class, "delete"]);
    });

    Route::prefix("tickets")->group(function () {
        Route::get("/", [TicketController::class, "list"]);
        Route::post("/create", [TicketController::class, "save"]);
        Route::get("/{id}", [TicketController::class, "edit"]);
        Route::post("/update", [TicketController::class, "update"]);
        Route::post("/delete", [TicketController::class, "delete"]);
        Route::post("/archive", [TicketController::class, "archive"]);
        Route::post("/change_status", [TicketController::class, "changeStatus"]);
        Route::get("/comments/{id}", [TicketController::class, "comments"]);
        Route::post("/comment", [TicketController::class, "saveComment"]);
        Route::get("/files/{id}", [TicketController::class, "files"]);
        Route::post("/file", [TicketController::class, "saveFile"]);
    });

    Route::prefix("inputs")->group(function () {
        Route::get("/", [InputController::class, "list"]);
        Route::post("/create", [InputController::class, "save"]);
        Route::get("/{id}", [InputController::class, "edit"]);
        Route::post("/update", [InputController::class, "update"]);
        Route::post("/delete", [InputController::class, "delete"]);
    });

    Route::prefix("categories")->group(function () {
        Route::get("/", [CategoryController::class, "list"]);
        Route::post("/create", [CategoryController::class, "save"]);
        Route::get("/{id}", [CategoryController::class, "edit"]);
        Route::post("/update", [CategoryController::class, "update"]);
        Route::post("/delete", [CategoryController::class, "delete"]);
    });

    Route::prefix("departments")->group(function () {
        Route::get("/", [DepartmentController::class, "list"]);
        Route::post("/create", [DepartmentController::class, "save"]);
        Route::get("/{id}", [DepartmentController::class, "edit"]);
        Route::post("/update", [DepartmentController::class, "update"]);
        Route::post("/delete", [DepartmentController::class, "delete"]);
    });

    Route::prefix("logs")->group(function () {
        Route::get("/", [LogController::class, "list"]);
        Route::post("/delete", [LogController::class, "delete"]);
    });

    Route::prefix("settings")->group(function () {
        Route::get("/", [SettingController::class, "settings"]);
        Route::post("/general/save", [SettingController::class, "saveGeneralSettings"]);
        Route::post("/mailserver/save", [SettingController::class, "saveMailServerSettings"]);
        Route::post("/social/save", [SettingController::class, "saveSocialSettings"]);
    });

    Route::prefix("dropdowns")->group(function () {
        Route::get("/tender", [DropdownController::class, "tenderDropdowns"]);
        Route::get("/project", [DropdownController::class, "projectDropdowns"]);
        Route::get("/client", [DropdownController::class, "clientDropdowns"]);
        Route::get("/task", [DropdownController::class, "taskDropdowns"]);
        Route::get("/event", [DropdownController::class, "eventDropdowns"]);
        Route::get("/sale", [DropdownController::class, "saleDropdowns"]);
        Route::get("/installation", [DropdownController::class, "installationDropdowns"]);
        Route::get("/change_order", [DropdownController::class, "changeOrderDropdowns"]);
        Route::get("/back_charge", [DropdownController::class, "backChargeDropdowns"]);
        Route::get("/punch_list", [DropdownController::class, "punchListDropdowns"]);
        Route::get("/employee", [DropdownController::class, "employeeDropdowns"]);
        Route::get("/logistic", [DropdownController::class, "logisticDropdowns"]);
        Route::get("/learning_material", [DropdownController::class, "learningMaterialDropdowns"]);
        Route::get("/ticket", [DropdownController::class, "ticketDropdowns"]);
        Route::get("/input", [DropdownController::class, "inputDropdowns"]);
        Route::get("/user", [DropdownController::class, "userDropdowns"]);
        Route::get("/search", [DropdownController::class, "searchDropdowns"]);
    });

    Route::get("/export/{slug}", [ExportController::class, "exportResource"]);
    Route::post("/comments/delete", [CommentController::class, "deleteComment"]);
});










































// Route::get("tasks", function () {
    //     return response()->json([
    //         "data" => [
    //             [
    //                 "id" => 1,
    //                 "assigned_to" => "Ayyan Khan",
    //                 "priority" => "High",
    //                 "status" => "active",
    //                 "start_date" => "23 aug 2022",
    //                 "end_date" => "23 jul 2022"
    //             ],
    //             [
    //                 "id" => 2,
    //                 "assigned_to" => "Zohaib Khan",
    //                 "priority" => "High",
    //                 "status" => "active",
    //                 "start_date" => "23 aug 2022",
    //                 "end_date" => "23 jul 2022"
    //             ],
    //             [
    //                 "id" => 3,
    //                 "assigned_to" => "Jawad Khan",
    //                 "priority" => "High",
    //                 "status" => "active",
    //                 "start_date" => "01 mar 2024",
    //                 "end_date" => "01 jul 2024"
    //             ],
    //             [
    //                 "id" => 4,
    //                 "assigned_to" => "Zeshan Ahmad",
    //                 "priority" => "High",
    //                 "status" => "active",
    //                 "start_date" => "01 feb 2015",
    //                 "end_date" => "01 jul 2015"
    //             ],
    //         ],
    //         "links" => [
    //             "first" => "",
    //             "last" => "",
    //             "prev" => "",
    //             "next" => ""
    //         ],
    //         "meta" => [
    //             "current_page" => 1,
    //             "last_page" => 4,
    //             "path" => "http://127.0.0.1:8000/api/managed-accounts",
    //             "per_page" => 10,
    //             "from" => 1,
    //             "to" => 4,
    //             "total" => 40
    //         ]

    //     ]);
    // });