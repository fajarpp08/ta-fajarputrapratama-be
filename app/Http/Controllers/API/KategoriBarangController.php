<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\GetKategoriBarangResource;
use App\Models\KategoriBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KategoriBarangController extends Controller
{
    public function index()
    {
        return DB::transaction(function() {
           $kategori_barang = KategoriBarang::orderBy("created_at", "DESC")
                ->get();
           
           return GetKategoriBarangResource::collection($kategori_barang);
        });
    }

    public function store(Request $request)
    {
        return DB::transaction(function () use ($request) {
            KategoriBarang::create([
                "barang_id" => $request->barang_id,
                "kategori_id" => $request->kategori_id
            ]);

            return response()->json([
                "status" => true,
                "pesan" => "Data Berhasi Ditambahkan"
            ]);
        });
    }

    public function update(Request $request, $id)
    {
        return DB::transaction(function() use ($request, $id) {
            KategoriBarang::where("id", $id)->update([
                "barang_id" => $request->barang_id,
                "kategori_id" => $request->kategori_id
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
            KategoriBarang::where("id", $id)->delete();

            return response()->json([
                "status" => true,
                "pesan" => "Data Berhasil di Hapus"
            ]);
        });
    }
}
