<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Installation extends Model
{
    use HasFactory;


    protected $fillable = [
        "project_id",
        "manager_id",
        // "client_id",
        "assigned_to",
        // "category_id",
        "installation_amount",
        "shipment_amount",
        "start_date",
        "end_date",
        "status",
        "notes",
        "archive"
    ];


    protected function casts(): array
    {
        return [
            'start_date' => 'datetime',
            'end_date' => 'datetime',
        ];
    }


    public function project()
    {
        return $this->belongsTo(Project::class, "project_id", "id");
    }


    public function manager()
    {
        return $this->belongsTo(User::class, "manager_id", "id");
    }


    // public function client ()
    // {
    //     return $this->belongsTo(User::class, "client_id", "id");
    // }


    public function assignee()
    {
        return $this->belongsTo(User::class, "assigned_to", "id");
    }


    // public function category ()
    // {
    //     return $this->belongsTo(Category::class, "category_id", "id");
    // }


    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function files()
    {
        return $this->morphMany(FileSystem::class, 'attachable');
    }
}
