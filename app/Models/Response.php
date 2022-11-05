<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Response extends Model
{
    protected $table = "tb_responses";
    protected $primaryKey = "id";
    protected $fillable = [
        "form_id",
        "user_id"
    ];
}
