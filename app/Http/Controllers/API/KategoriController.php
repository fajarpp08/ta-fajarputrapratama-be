<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\GetKategoriResource;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KategoriController extends Controller
{
    public function index()
    {
        return DB::transaction(function() {
            $kategori = Kategori::orderBy("created_at", "DESC")
                ->get();

            return GetKategoriResource::collection($kategori);
        });
    }

    public function store(Request $request)
    {
        return DB::transaction(function() use ($request) {
            Kategori::create([
                "name" => $request->name
            ]);

            return response()->json([
                "status" => true,
                "pesan" => "Data Berhasil di Tambahkan"
            ]);
        });
    }

    public function update(Request $request, $id)
    {
        return DB::transaction(function() use ($request, $id) {
            Kategori::where("id", $id)->update([
                "name" => $request->name 
            ]);

            return response()->json([
                "status" => true,
                "pesan" => "Data Berhasil di Simpan"
            ]);
        });
    }

    public function destroy($id)
    {
        return DB::transaction(function() use ($id) {
            Kategori::where("id", $id)->delete();

            return response()->json([
                "status" => true,
                "pesan" => "Data Berhasil di Hapus"
            ]);
        });
    }
}
