<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\{Form, AllowedDomain};
use App\Models\Response;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ResponsesController extends Controller
{
    public function create(Request $req, $slug)
    {
        $validator = Validator::make($req->all(), [
            "answer" => "array",
        ]);

        
        if($validator->fails())
        {
            return response()->json([
                "message" => "invalid field",
            ], 422);
        }
        $data = Form::where("slug", $slug)->first();

        $allowed_domain = AllowedDomain::where("form_id", $data->id)->get();
        $arrayDomain = [];
        for($i = 0; $i < count($allowed_domain); $i++) {
            $arrayDomain[] = $allowed_domain[$i]->domain;
        }
         if(!in_array(explode("@", auth()->user()->email)[1],$arrayDomain) && !empty($allowed_domain)) {
            return response()->json([
                "message" => "forbidden",
            ], 400);
        }  else if(($data->creator_id === auth()->user()->id)) {
            return response()->json([
                "message" => "creator cannot response",
            ], 400);
        } else if(!empty(Response("user_id", auth()->user()->id)->first()))
        {
            return response()->json([
                "message" => "You can not submit form twice",
            ], 400);
        }


         $response = new Response;
         $answer = new Answer;

      
            for($i = 0; $i < count($req->answer); $i++)
            {
                $res = $response->create([
                    "form_id" => Form::where("slug", $slug)->first()->id,
                    "user_id" => auth()->user()->id,
                    "date" => Carbon::now()
                ]);
    
                $ans = $answer->create([
                    "response_id" => $res->id,
                    "question_id" => $req->answer[$i]["question_id"],
                    "value" => $req->answer[$i]["value"]
                ]);
            }


        

        return $ans;

        
    }
}
