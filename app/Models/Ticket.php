<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        "subject",
        "category_id",
        "owner_id",
        "status",
        "description"
    ];


    public function category()
    {
        return $this->belongsTo(Category::class, "category_id", "id");
    }


    public function owner()
    {
        return $this->belongsTo(User::class, "owner_id", "id");
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
