<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;


    protected $fillable = [
        "user_id",
        "position_id",
        "department_id",
        "employment_type",
        "notes"
    ];


    public function profile()
    {
        return $this->belongsTo(User::class, "user_id", "id");
    }


    public function position()
    {
        return $this->belongsTo(Position::class, "position_id", "id");
    }


    public function department()
    {
        return $this->belongsTo(Department::class, "department_id", "id");
    }
}
