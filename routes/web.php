<?php

use App\Exports\ProjectExport;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LogController;
use App\Http\Resources\LearningMaterialResource;
use App\Http\Resources\TaskCollection;
use App\Http\Resources\TaskResource;
use App\Models\BackCharge;
use App\Models\ChangeOrder;
use App\Models\Employee;
use App\Models\Event;
use App\Models\FileSystem;
use App\Models\Folder;
use App\Models\LearningMaterial;
use App\Models\Task;
use App\Models\Ticket;
use App\Models\User;
use App\Notifications\GeneralNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

use Maatwebsite\Excel\Facades\Excel;
use Spatie\Activitylog\Models\Activity;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

Route::get("/departments", [DepartmentController::class, "list"]);

Route::get('/info', function () {
//FileSystem::whereIn("id", [1, 5, 6])->delete();
//dd(FileSystem::all());
//$user = User::find(4);
//FileSystem::destroy(4);
//$user->employee()->delete();
//dd($user->delete());

    return "f**k u";

    dd(Activity::all());

    $bc = BackCharge::all();

    dd($bc);
    $user = User::find(1);

    $permissions = Permission::select("name")->get()->pluck("name")->toArray();
    $user->syncPermissions($permissions);

    dd($user);

    dd($user->permissions->pluck("name", "id"));

    dd(User::with(['roles', 'permissions'])->get());

    // return Excel::download(new ProjectExport, 'projects.xlsx');


    // if (Storage::disk('local')->exists("uploads/my-docs/important-docs")) {
    //     $d = Storage::disk('local')->deleteDirectory("uploads/my-docs/important-docs");

    //     dd($d);
    // }

    // dd("here");

    // FileSystem::where("id", "<>", null)->delete();
    // FileSystem::create([
    //     "name" => "Test",
    //     "slug" => "test",
    //     "path" => "uploads/test",
    //     "type" => "directory",
    // ]);

    // dd(FileSystem::all());
    dd(User::find(1)->notifications->pluck("data", "id")->toArray());

    // Folder::create([
    //     "name" => "avatars",
    //     "category_id" => 6
    // ]);
    // Folder::create([
    //     "name" => "learning_materials",
    //     "category_id" => 6
    // ]);
    // Folder::create([
    //     "name" => "photos",
    //     "category_id" => 6
    // ]);
    // Folder::create([
    //     "name" => "project_files",
    //     "category_id" => 6
    // ]);
    // Folder::create([
    //     "name" => "punch_lists",
    //     "category_id" => 6
    // ]);
    // Folder::create([
    //     "name" => "sales_files",
    //     "category_id" => 6
    // ]);

    // dd("hello");

    // User::find(1)->notify(new GeneralNotification(null, "created", "test"));
    // User::find(1)->notify(new GeneralNotification(null, "created", "test 2"));
    // User::find(1)->notify(new GeneralNotification(null, "created", "test 3"));

    // dd(
    //     User::find(1)->notifications
    // );

    // Task::find(1)->update([
    //     "end_date" => Carbon::createFromFormat("Y-m-d", "2024-08-02")
    //     // "dob" => Carbon::createFromFormat("Y-m-d", "2024-07-01")
    // ]);
    // dd(Task::find(1));

    // User::find(1)->update([
    //     // "dob" => Carbon::createFromFormat("Y-m-d", "1997-04-28")
    //     "dob" => Carbon::createFromFormat("Y-m-d", "2024-07-01")
    // ]);

    dd(User::find(1));

    dd(
        Carbon::createFromFormat("Y-m-d", "1997-04-28")->format("d M Y")
    );

    dd((new DashboardController())->index());

    dd(Event::all());

    dd(User::with(["roles"])->get());

    // dd(ChangeOrder::all());
    // User::destroy(7);
    // return "okay";
    // dd(Employee::where("user_id", 2)->get());

    Role::create(['name' => 'Administrator']);
    Role::create(['name' => 'CEO']);
    Role::create(['name' => 'Director']);
    Role::create(['name' => 'Finance Manager']);
    Role::create(['name' => 'Project Manager']);
    Role::create(['name' => 'HR']);
    Role::create(['name' => 'Accountant']);
    Role::create(['name' => 'Software Engineer']);

    return "done";
    // Permission::create(['name' => 'edit articles']);


    // $user = User::find(6)->update(["is_client" => false]);

    // $user->avatar = "uploads/avatars/1720610872_668e703882bbf.png";
    // $user->save();
    dd($user);
    dd(Employee::with(['department'])->get());
    dd(User::where("is_client", 1)->get());
    dd(LearningMaterial::find(1));
    dd(Ticket::with('photos')->find(8));
    // User::all()->dd();
    // Task::all()->dd();

    $tasks = Task::with("assignedTo")->paginate(10);
    // return $tasks;

    return new TaskCollection(TaskResource::collection($tasks));


    dd($tasks);
    return new TaskResource($tasks);
});

require __DIR__ . '/auth.php';
