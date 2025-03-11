<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;


    protected $fillable = [
        "event_name",
        "client_id",
        "assigned_to",
        "due_date",
        "department_id",
        "status",
        "notes"
    ];


    protected function casts(): array
    {
        return [
            "due_date" => "datetime"
        ];
    }


    public function client()
    {
        return $this->belongsTo(User::class, "client_id", "id");
    }


    public function assignee()
    {
        return $this->belongsTo(User::class, "assigned_to", "id");
    }


    public function department()
    {
        return $this->belongsTo(Department::class, "department_id", "id");
    }


    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }


    // public function files()
    // {
    //     return $this->morphMany(Photo::class, 'photoable');
    // }


    public function files()
    {
        return $this->morphMany(FileSystem::class, 'attachable');
    }
}
