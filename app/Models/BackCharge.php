<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BackCharge extends Model
{
    use HasFactory;


    protected $fillable = [
        // "client_id",
        "project_id",
        "date",
        "floor",
        "unit_number",
        "description_of_change",
        "description_of_change_approval",
        "reason_for_change",
        "reason_for_change_approval",
        "additional_notes",
        "fix_amount",
        "charge_amount",
        "york_signature",
        "client_signature",
        "archive"
    ];


    protected function casts(): array
    {
        return [
            'date' => 'datetime',
        ];
    }


    public function project()
    {
        return $this->belongsTo(Project::class, "project_id", "id");
    }


    // public function client()
    // {
    //     return $this->belongsTo(User::class, "client_id", "id");
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
