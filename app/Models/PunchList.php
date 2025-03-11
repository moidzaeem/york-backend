<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PunchList extends Model
{
    use HasFactory;

    protected $fillable = [
        // "client_id",
        "project_id",
        "manager_id",
        "assigned_to",
        "end_date",
        "address",
        "priority",
        "status",
        "notes",
        "archive"
    ];


    protected function casts(): array
    {
        return [
            'end_date' => 'datetime',
        ];
    }


    // public function client ()
    // {
    //     return $this->belongsTo(User::class, "client_id", "id");
    // }


    public function project()
    {
        return $this->belongsTo(Project::class, "project_id", "id");
    }


    // public function manager()
    // {
    //     return $this->belongsTo(User::class, "manager_id", "id");
    // }


    public function assignee()
    {
        return $this->belongsTo(User::class, "assigned_to", "id");
    }


    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }


    public function files()
    {
        return $this->morphMany(FileSystem::class, 'attachable');
    }
}
