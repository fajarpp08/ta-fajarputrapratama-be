<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ValidatorLoginRequest;
use App\Http\Requests\ValidatorRegister;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AutentikasiController extends Controller
{
    public function index()
    {
        return DB::transaction(function() {
            return response()->json([
                "status" => false,
                "pesan" => "Anda Harus Login Terlebih Dahulu"
            ]);
        });
    }
    
    public function login(ValidatorLoginRequest $request)
    {
        return DB::transaction(function() use ($request) {
            $user = User::where("email", $request->email)->first();
            
            if (!$user) {
                return response()->json([
                    "status" => false,
                    "pesan" => "Email Tidak Terdaftar"
                ]);
            }
            
            $cek_password = Hash::check($request->password, $user->password);
            
            if (!$cek_password) {
                return response()->json([
                    "status" => false,
                    "pesan" => "Password Salah"
                ]);
            }
            
            $token = $user->createToken("api", [$user->name]);

            Auth::login($user);

            $user["token"] = $token->plainTextToken;
            
            return response()->json([
                "status" => false,
                "pesan" => "Berhasil Login",
                "data" => $user
            ]);
        });
    }

    public function register(ValidatorRegister $request)
    {
        return DB::transaction(function() use ($request) {
            User::create([
                "name" => $request->name,
                "email" => $request->email,
                "password" => bcrypt($request->password),
                "role_id" => 3
            ]);

            return response()->json([
                "status" => true,
                "pesan" => "Data Berhasil di Tambahkan"
            ]);
        });
    }
    
    public function logout()
    {
        return DB::transaction(function() {
            $user = Auth::user();
            
            $user->tokens()
            ->where("id", $user->currentAccessToken()->id)
            ->delete();
            
            return response()->json([
                "status" => false,
                "pesan" => "Berhasil Logout"
            ]);
        });
    }
}
