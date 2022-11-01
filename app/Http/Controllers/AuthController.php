<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Validator};

class AuthController extends Controller
{

    public function login(Request $req)
    {
        $validator = Validator::make($req->all(), [
            "email" => "required",
            "password" => "min:5|required"
        ]);

        if($validator->fails())
        {
            return response()->json([
                "message" => "invalid field",
                "errors" => [
                    "email" => [
                        "The email must be a valid email address."
                    ],
                    "password" => [
                        "The password field is required."
                    ]
                ]
            ], 422);
        }

        if(!$token = 
        auth()->setTTL(1440)->attempt(["email" => $req->email, "password" => $req->password])) {  
            return response()->json([
                "message" => "Email or password incorrect"
            ]);
        }

        return response()->json([
            "access_token" => $token,
            "token_type" => "Bearer",
            "expires_in" => 1440
        ], 200);
    }

    public function me()
    {
        return auth()->user();
    }

    public function logout()
    {
        auth()->logout();
        return response()->json([
            "message" => "logout success"
        ], 200);
    }
}
