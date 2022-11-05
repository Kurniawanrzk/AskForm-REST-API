<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $table = "tb_answers";
    protected $primaryKey = "id";
    protected $fillable = [
        "response_id",
        "question_id",
        "value",
    ];
}
