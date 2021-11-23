<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Auth;

class AuthController extends Controller
{
    public function gsignin(Request $request) {
        
    }

    public function login(Request $request) {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8'
        ]);

        if(!Auth::attempt(['email' => $request["email"], 'password' => $request["password"]])) {
            return response()->json(["message" => "unauthorized"], 401);
        }

        $user = User::where("email", $credentials["email"])->first();
        if(!\Hash::check($credentials["password"], $user["password"])) {
            return response()->json(["message" => "email and password missmatch"], 401);
        }

        $token = $user->createToken('token')->plainTextToken;

        return response()->json(["message" => "success", "token"=> $token]); 
    }

    public function register(Request $request) {
        $validated = $request->validate([
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'name' => 'required'
        ]);

        $user = User::create([
            "name" => $request["name"],
            "email" => $request["email"],
            "password" => \Hash::make($request["password"])
        ]);
        return response()->json(["message" => "register success"], 201);
    }

}
