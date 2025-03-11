<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "company_name",
        "phone_gsm",
        "phone_stable",
        "status"
    ];


    public function profile()
    {
        return $this->belongsTo(User::class, "user_id", "id");
    }
}
