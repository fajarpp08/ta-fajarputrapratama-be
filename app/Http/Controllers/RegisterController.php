<?php

namespace App\Http\Controllers;

use App\Mail\MailRegisterConfirm;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Psy\Util\Str;

class RegisterController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'string|required|min:3',
            'email' => 'required|email|unique:users,email',
            'password' =>'required|string|min:6|max:40',
        ]);

        $signature =  \Illuminate\Support\Str::uuid();
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role_id = Role::where('name','user')->first()->id;
        $user->password = Hash::make($request->password);
        $user->remember_token = $signature;
        $user->save();


        $url=  url('/api/register/confirmation/'.$user->id).'?signature='.$signature;
        // $url=  url("https://digitm.isoae.com");

        Mail::to($user->email)->queue(new MailRegisterConfirm($user,$url));

        return $this->success($user,'Register berhasil! Silahkan cek email anda untuk melakukan verifikasi');
    }
    public function confirmation(Request $request,$id){
        $key = $request->signature;
        $user = User::where('id',$id)->where('remember_token',$key)->first();
        if($user){
            $user->email_verified_at = Carbon::now();
            $user->save();
            return $this->success($user,'Verifikasi email berhasil');
        }
        return $this->error("UNAUTHORIZED","Verifikasi gagal",401);
    }
}
