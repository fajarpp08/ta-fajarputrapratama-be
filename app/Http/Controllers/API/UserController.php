<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateAdmin;
use App\Http\Requests\CreateUser;
use App\Http\Resources\GetUserAdminResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        return DB::transaction(function() {
            $data = User::where("role_id", 2 )
                ->orderBy("created_at", "DESC")
                ->get();

            return GetUserAdminResource::collection($data);
        });
    }

    public function store(CreateUser $request)
    {
        return DB::transaction(function() use ($request) {
            User::create([
                "name" => $request->name,
                "email" => $request->email,
                "password" => bcrypt($request->password),
                "role_id" => 3 
            ]);
        }); 
    }

    public function show($id)
    {
        return DB::transaction(function() use ($id) {
            $user = User::where("id", $id)->first();

            return new GetUserAdminResource($user);
        });
    }

    public function update(CreateAdmin $request, $id)
    {
        return DB::transaction(function() use ($request, $id) {
            User::where("id", $id)->update([
                "name" => $request->name,
                "email" => $request->email
            ]);

            return response()->json([
                "status" => false,
                "pesan" => "Data Berhasil di Simpan"
            ]);
        });
    }

    public function delete($id)
    {
        return DB::transaction(function() use ($id) {
            User::where("id", $id)->delete();

            return response()->json([
                "status" => false,
                "pesan" => "Data Berhasil di Hapus"
            ]);
        });
    }

    public function store_admin(CreateAdmin $request)
    {
        return DB::transaction(function() use ($request) {
            User::create([
                "name" => $request->name,
                "email" => $request->email,
                "password" => bcrypt($request->password),
                "role_id" => 2 
            ]);
        });
    }
}
