<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        "title",
        "client_id",
        "department_id",
        "category_id",
        "start_date",
        "end_date",
        "status",
        "notes",
        "priority",
        "archive"
    ];


    protected function casts(): array
    {
        return [
            'start_date' => 'datetime',
            'end_date' => 'datetime',
        ];
    }


    public function assignee()
    {
        return $this->belongsTo(User::class, "assigned_to", "id");
    }


    public function client()
    {
        return $this->belongsTo(User::class, "client_id", "id");
    }


    public function department()
    {
        return $this->belongsTo(Department::class, "department_id", "id");
    }


    public function category()
    {
        return $this->belongsTo(Category::class, "category_id", "id");
    }


    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }


    public function files()
    {
        return $this->morphMany(FileSystem::class, 'attachable');
    }

    public function assignedTo()
    {
        return $this->belongsToMany(User::class);
    }

    public function users(){
        return $this->belongsToMany(User::class, 'task_user', 'task_id', 'user_id');
    }
}
