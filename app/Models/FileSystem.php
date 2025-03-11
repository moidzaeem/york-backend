<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileSystem extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "slug",
        "path",
        "type",
        "mimetype",
        "parent_id",
        "attachable_type",
        "attachable_id",
        "owner_id",
    ];

    public function parent()
    {
        return $this->belongsTo(FileSystem::class, 'parent_id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function children()
    {
        return $this->hasMany(FileSystem::class, 'parent_id');
    }

    public function attachable()
    {
        return $this->morphTo();
    }
}
