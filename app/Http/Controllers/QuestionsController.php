<?php

namespace App\Http\Controllers;

use App\Models\{Question, Form};
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class QuestionsController extends Controller
{
    public function create(Request $req, $slug)
    {
        $validator = Validator::make($req->all(), [
            "name" => "required",
            "choice_type" => 'in:short answer,paragraph,date,multiple choice,dropdown,checkboxes|required',
            "choices" => 
            "required_if:choice_type,==,multiple choice,dropdown,checkboxes"
        ]);
        
        if($validator->fails()) {
            return response()->json([
                "message" => "Field invalid"
            ], 422);
        }

        $req->merge([
            "choices" =>  implode(",", $req->choices),
            "form_id" => Form::where("slug", $slug)->first()->id
        ]);
        $question = new Question;
        $create = $question->create($req->all());

        return response(Question::where("id",$create->id)->first(), 200);

    }
}
