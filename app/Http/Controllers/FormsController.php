<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\{Form, AllowedDomain, Question, User};


class FormsController extends Controller
{
    public function create(Request $req)
    {
        $validator = Validator::make($req->all(), [
            "name" => "required",
            "slug" => "required|regex:/^[A-Za-z. -]+$/",
            "allowed_domains" => "present|array",
        ]);

        if($validator->fails())
        {
            return response()->json([
                "message" => "Invalid field",
                "errors" => [
                    "name" => [
                    "The name field is required."
                    ],
                    "slug" => [
                    "The slug has already been taken."
                    ],
                    "allowed_domains" => [
                    "The allowed domains must be an array."
                    ]
                ]
            ]);
        }

        if(!empty(Form::where("slug", $req->slug)->first()))
        {
            return response()->json([
                "message" => "Change the slug, already exist!"
            ],401);
        }

        $form = new Form;
        $allowed_domain = new AllowedDomain;
        $create_form = $form->create([
            "name" => $req->name,
            "description" => $req->description,
            "slug" => $req->slug,
            "limit_one_response" => $req->limit_one_response,
            "creator_id" => auth()->user()->id
        ]);

        if(!empty($req->allowed_domains)) {
            for($i = 0; $i < count($req->allowed_domains); $i++) {
                $allowed_domain->create([
                    "form_id" => Form::where("name", $req->name)->first()->id,
                    "domain" => $req->allowed_domains[$i]
                ])->save();
            }
        }

        return response()
        ->json(Form::where("id", $create_form->id)
        ->first(), 200);


    }

    public function getAll()
    {
        return Form::all();
    }

    public function detailForm(Request $req, $slug)
    {
        if(!$data = Form::where("slug", $slug)->first() ) {
            return response()->json([
                "message" => "From not found"
            ], 401);
        }
        $allowed_domain = AllowedDomain::where("form_id", $data->id)->get();
        $arrayDomain = [];
        for($i = 0; $i < count($allowed_domain); $i++) {
            $arrayDomain[] = $allowed_domain[$i]->domain;
        }
        
        $realDetailForm = [
            "id" => $data->id,
            "name" => $data->name,
            "slug" => $data->slug,
            "description" => $data->description,
            "limit_one_response" => $data->limit_one_response,
            "creator_id" => $data->creator_id,
            "allowed_domains" => $arrayDomain,
            "questions" => Question::where("form_id", $data->id)->get()

        ];


        if(empty($arrayDomain)){
            return response()->json([
                "message" => "Get form success",
                "form" => $realDetailForm,
            ], 200);

        }else if(in_array(explode("@", auth()->user()->email)[1],$arrayDomain))
        {
            return response()->json([
                "message" => "Get form success",
                "form" => $realDetailForm,
            ], 200);

        } else if(!in_array(explode("@", auth()->user()->email)[1],$arrayDomain) && ($data->creator_id !== auth()->user()->id)) {
            return response()->json([
                "message" => "forbidden",
            ], 400);

        } else if($data->creator_id === auth()->user()->id) {
            return response()->json([
                "message" => "Get form success",
                "form" => $realDetailForm,
            ], 200);
        }



    }
}
