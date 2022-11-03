<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    public function inscription(Request $request)
    {
        # code...
        //return $request->all();
        $user = $request->validate(
            [
                "name" => "string|required|min:2|max:100",
                "email" => "required|email|unique:users,email",
                "password" => "required|string|min:6|max:30|confirmed"
            ]
        );

        $user = User::create([
            "name" => $user["name"],
            "email" => $user["email"],
            "password" => bcrypt($user["password"])
        ]);

        return response($user, 201);
    }

    public function connexion(Request $request)
    {
        # code...
        $user = $request->validate(
            [
                "email" => "required|email",
                "password" => "required"
            ]
        );
        $utulisateur = User::where("email", $user["email"])->first();
        if (!$utulisateur)
            return response(["message" => "pas d'utulisateur avec ce mail $user[email]"]);

        $token = $utulisateur->createToken("nianiaclef")->plainTextToken;
        return response([
            "token" => $token,
            "utulisateur" => $utulisateur
        ]);
    }
}
