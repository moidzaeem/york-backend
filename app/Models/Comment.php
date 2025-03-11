<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'comment',
        'owner_id',
        'commentable_id',
        'commentable_type',
    ];


    /**
     * Get the parent commentable model (user, post, etc.).
     */
    public function commentable()
    {
        return $this->morphTo();
    }

    public function owner()
    {
        return $this->belongsTo(User::class, "owner_id", "id");
    }
}
