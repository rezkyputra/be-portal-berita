<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\otpCode;
use App\Models\Roles;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegisterMailSend;

class AuthController extends Controller
{
    public function register(Request $request){
        $request->validate([
            'name' => 'required',
            'password' => 'required|min:6',
            'email' => 'required|email|unique:users,email',
        ], [
            'required' => 'inputan :attribute harus diisi.'
        ]);

        $rolesData = Roles::where('name', 'user')->first();

        $user = User::create([
            "name" => $request->input('name'),
            "email" => $request->input('email'),
            "password" => Hash::make($request->input('password')),
            "role_id" => $rolesData->id
        ]);

        $userData = User::with(['profile', 'role', 'historyComment'])->where('id', $user->id)->first();

        $user->generateOtpCodeData();
        $token = JWTAuth::fromUser($user);

        Mail::to($user->email)->send(new RegisterMailSend($user));

        return response([
            "message" => "Register Berhasil silahkan check email anda",
            "token" => $token,
            "data" => $userData
        ], 201);
    }

    public function generateOtp(Request $request){
        $request->validate([
            'email' => 'required|email'
        ]);

        $userData = User::where('email', $request->email)->first();

        $userData->generateOtpCodeData();

        return response([
            "message" => "Otp Code berhasil digenerate silahkan check email anda",
        ], 201);
    }

    public function verifikasi(Request $request){
        $request->validate([
            'otp' => 'required'
        ]);

        //check apakah otp code yang diinput sama dengan yang ada di db
        $otp_code = otpCode::where('otp', $request->input('otp'))->first();

        if(!$otp_code){
            return response([
                "message" => "Otp yang dimasukan salah",
            ], 400);
        }

        $now = Carbon::now();
        // jika otp code sudah kadaluarsa
        if($now > $otp_code->valid_until){
            return response([
                "message" => "Otp sudah kadaluarsa, silahkan generate ulang",
            ], 400);
        }

        //update di table user kolom email verifikasi
        $user = User::find($otp_code->user_id);
        $user->email_verified_at = $now;

        $user->save();

        //delete otp code nya
        $otp_code->delete();

        return response([
            "message" => "Berhasil verifikasi akun",
        ], 200);
    }

    public function login(Request $request){
        $credentials = request(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Invalid User'], 401);
        }

        
        $user = User::with(['profile', 'role', 'historyComment'])->where('email', $request['email'])->first();

        return response([
            "message" => "Login Berhasil",
            "token" => $token,
            "data" => $user
        ], 200);
    }

    public function getUser()
    {
        $currentUser = auth()->user();

        $userandProfile = User::with(['profile', 'role', 'historyComment'])->find($currentUser->id);

        return response([
            "message" => "Get User Berhasil",
            "data" => $userandProfile
        ], 200);
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(
            [
                'message' => 'Successfully logged out'
            ]
        );
    }
}
