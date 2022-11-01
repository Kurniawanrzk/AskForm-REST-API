<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    protected $table = "tb_forms";
    protected $primaryKey = "id";
    protected $fillable = [
        "name",
        "slug",
        "description",
        "limit_one_response",
        "creator_id",
    ];
}
