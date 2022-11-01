<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $table = "tb_questions";
    protected $primaryKey = "id";
    protected $fillable = [
        "form_id",
        "name",
        "choice_type",
        "choices",
        "is_required"
    ];
}
