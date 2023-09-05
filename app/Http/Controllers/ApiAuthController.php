<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ApiAuthController extends Controller
{
    public function login(Request $request){

        $request->validate([
             'email' => 'required|email',
             'password' => 'required|string',
        ]);
         $user = User::where('email', $request->email)->first();
        if (!$user){
           return $this->error("UNAUTHORIZED","Email tidak ditemukan",401);
        }
        if (!Hash::check($request->password, $user->password)){
            return $this->error("UNAUTHORIZED","Password tidak valid",401);
        }

        if(!$user->email_verified_at){
            return $this->error("UNAUTHORIZED","Alamat email belum terverifikasi",401);
        }
        $userTokens = $user->tokens;
        if ($userTokens != null) {
            foreach ($userTokens as $token) {//hapus semua token user
                //$token->revoke();//-->dihilangin kalau token lama tidak di hapus, multi device login
                //$token->delete(); //-->dihilangin kalau token lama tidak di hapus, multi device login
            }
        }


        $tokenResult = $user->createToken('Personal Access Token '.$user->name,[]);
        $token = $tokenResult->token;
          //jika setting expired token dengan periode tertentu
        $token->expires_at = Carbon::now()->addHours(1);
        $token->save();

        $access['access_token'] = $tokenResult->accessToken;
        $access['token_type'] = 'Bearer';
        $access['expires_in'] = $token->expires_at->format('Y-m-d H:i');

        $data['record'] = User::with('role')->findOrFail($user->id);
        $data['token'] = $access;
        return $this->success($data, "Login success");

    }
    public function cekValidToken(Request $request): JsonResponse
    {
        $user = $request->user();
        $data['record'] = User::with('role')->findOrFail($user->id);
        return $this->success($data, "token valid");
    }
    public function logout(Request $request){
        $user = Auth::user();
        $userTokens = $user->tokens;
        if ($userTokens != null) {
            foreach ($userTokens as $token) {//hapus semua token user
                $token->revoke();//-->dihilangin kalau token lama tidak di hapus, multi device login
                $token->delete(); //-->dihilangin kalau token lama tidak di hapus, multi device login
            }
        }
        return $this->success(null, "logout success");


    }
}
