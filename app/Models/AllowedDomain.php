<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AllowedDomain extends Model
{
    protected $table = "tb_allowed_domains";
    protected $primaryKey = "id";
    protected $fillable = [
        "form_id",
        "domain"
    ];
}
