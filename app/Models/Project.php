<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        "project_name",
        "client_id",
        "manager_id",
        "category_id",
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


    public function client()
    {
        return $this->belongsTo(User::class, "client_id", "id");
    }


    public function manager()
    {
        return $this->belongsTo(User::class, "manager_id", "id");
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
}
