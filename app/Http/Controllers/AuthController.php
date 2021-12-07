<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\OtpCode;
use App\Mail\OtpVerification;

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

        $characters = '0123456789';

        // generate a pin based on 5 digits + a random character
        $pin = mt_rand(10000, 99999)
            . $characters[rand(0, strlen($characters) - 1)];

        // shuffle the result
        $string = str_shuffle($pin);

        $otp_codes = OtpCode::create([
            "otp_code" => $string,
            "user_id" => $user->id
        ]) ;

        Mail::to($user)->send(new OtpVerification($otp_codes, $user->name));

        // 1. generate OTP token (random 6 digit)
        // 2. token disimpan tabel otp_codes
        // 3. yang disimpan adalah tokennya dan user_id
        // 4. kirim email berisikan kode OTP
        // 5. return response => "message" => "register success, please check your email for your OTP tokens"


        return response()->json(["message" => "register success"], 201);
    }

    // konfirmasi OTP
    public function otp_confirmation(Request $request) {
        // 1. mengecek email dari request
        // 2. di tabel user cari user dengan email tersebut
        // 3. dapat id user => dicek di tabel otp_codes
        // 4. jika user_id ditemukan => dicek kode otp dari request dan dari database cocok atau tidak
        // 5. kalau cocok, user.is_verified dibuat true, return response success
        // 6. kalau tidak cocok kembalikan pesan error
        $user = User::where('email', $request['email'])->first();
        $user_id = $user->id;
        $otp = OtpCode::where('user_id', $user_id)->where('otp_code', $request['otp_code'])->first();

        if (!$otp) {
            return response()->json([
                'message' => 'otp code is invalid!'
            ], 400);
        } else {
            $user->update([
                'is_verified' => true
            ]);
            return response()->json([
                'message' => 'success confirmed OTP, you may signin now'
            ]);
        }
    }

}
