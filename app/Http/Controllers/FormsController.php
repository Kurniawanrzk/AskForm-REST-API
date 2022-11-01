<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\{Form, AllowedDomain, User};


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

        if(!empty(Form::where("name", $req->name)->first()))
        {
            return response()->json([
                "message" => "Form already exsist"
            ],401);
        }

        $form = new Form;
        $allowed_domain = new AllowedDomain;
        $form->create([
            "name" => $req->name,
            "description" => $req->description,
            "slug" => $req->slug,
            "limit_one_response" => $req->limit_one_response,
            "creator_id" => auth()->user()->id
        ])->save();

        if(!empty($req->allowed_domains)) {
            for($i = 0; $i < count($req->allowed_domains); $i++) {
                $allowed_domain->create([
                    "form_id" => Form::where("name", $req->name)->first()->id,
                    "domain" => $req->allowed_domains[$i]
                ])->save();
            }
        }

        return response()
        ->json(Form::where("name", $req->name)
        ->first(), 200);

    }

    public function getAll()
    {}
}
