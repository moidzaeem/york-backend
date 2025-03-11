<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeRequest;
use App\Http\Resources\EmployeeResource;
use App\Mail\UserCredentialsMail;
use App\Models\Employee;
use App\Models\User;
use App\Services\DatatableService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class EmployeeController extends Controller
{

    public function list(Request $rq)
    {

        try {
            return (new DatatableService())->create(Employee::with(["position", "department", "profile"]), EmployeeResource::class);
        } catch (\Throwable $th) {
            throw $th;
            return response()->json([
                "status" => 500,
                "message" => $th?->getMessage()
            ]);
        }
    }


    public function edit($id)
    {
        try {

            request()->merge(["all_attributes" => true]);

            return new EmployeeResource(
                Employee::with(['profile', 'profile.roles', 'profile.permissions'])->findOrFail($id)
            );
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 404,
                "message" => $th?->getMessage()
            ]);
        }
    }


    public function update(EmployeeRequest $rq)
    {
        try {

            // Employee::find($rq->id)->update($rq->validated());

            // Update User
            $user = User::find($rq->user_id);

            $avatar = $user->avatar;

            if ($rq->hasFile("files")) {

                $this->handleFileDeletion($user->avatar);

                $files = $rq->file('files');

                $avatar = $this->handleFileUpload($files, "Avatars", false);
            }

            $user->update(
                [
                    ...$rq->safe()->only([
                        'name',
                        'phone_number',
                        'email',
                        'address',
                        'dob'
                    ]),
                    $avatar
                ]
            );


            // Update Employee
            $user->employee()->update($rq->safe()->only([
                'position_id',
                'department_id',
                'employment_type',
                'notes'
            ]));

            $user->syncRoles([$rq->safe()->only(['role'])]);
            $user->syncPermissions($rq->safe()->only(['permissions']));


            activity()
                ->performedOn($user)
                ->log(':causer.name modified employee (:subject.name)');

            return response()->json([
                "status" => 201,
                "message" => "Employee updated successfully"
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 404,
                "message" => $th?->getMessage()
            ]);
        }
    }


    public function save(EmployeeRequest $rq)
    {
        try {

            $files = $rq->file('files');

            $avatar = $this->handleFileUpload($files, "Avatars", false);

            // Create User
            $user = User::create([
                ...$rq->safe()->only([
                    'name',
                    'phone_number',
                    'email',
                    'address',
                    'dob'
                ]),
                "avatar" => $avatar,
                'special' => false,
                'blacklist' => false,
                'is_client' => false,
                "password" => Hash::make($rq->phone_number)
            ]);



            // Create Employee
            $user->employee()->create($rq->safe()->only([
                'position_id',
                'department_id',
                'employment_type',
                'notes'
            ]));

            // grant permissions or role
            $user->syncRoles([$rq->safe()->only(['role'])]);
            $user->syncPermissions($rq->safe()->only(['permissions']));


            activity()
                ->performedOn($user)
                ->log(':causer.name created employee (:subject.name)');

            Mail::to($rq->email)->send(new UserCredentialsMail([
                "user_name" => $rq->name,
                "email" => $rq->email,
                "password" => $rq->phone_number,
            ]));

            return response()->json([
                "status" => 201,
                "message" => "Employee created successfully"
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 404,
                "message" => $th?->getMessage()
            ]);
        }
    }


    public function delete(Request $rq)
    {
        try {

            $user = User::find($rq->id);
            $user->employee()->delete();

            // $this->handleFileDeletion($user->avatar);


            activity()
                ->performedOn($user)
                ->log(':causer.name deleted employee (:subject.name)');

            $user->delete();

            return response()->json([
                "status" => 201,
                "message" => "Employee deleted successfully"
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 404,
                "message" => $th?->getMessage()
            ]);
        }
    }


    public function archive(Request $rq)
    {
        try {

            $model = Employee::find($rq->id);
            $model->update(["archive" => !$model->archive]);

            $op = $model->archive ? "Archived" : "Unarchived";


            activity()
                ->performedOn($model)
                ->log(':causer.name ' . $op . ' employee (:subject.profile.name)');

            return response()->json([
                "status" => 201,
                "message" => "Employee {$op} successfully"
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 404,
                "message" => $th?->getMessage()
            ]);
        }
    }
}
