<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        "client_id",
        "sales_person_id",
        "category_id",
        "offer_price",
        "date",
        "status",
        "notes",
        "archive"
    ];


    protected function casts(): array
    {
        return [
            'date' => 'datetime',
        ];
    }


    public function client()
    {
        return $this->belongsTo(User::class, "client_id", "id");
    }


    public function salesPerson()
    {
        return $this->belongsTo(User::class, "sales_person_id", "id");
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
