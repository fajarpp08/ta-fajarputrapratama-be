<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\GetBarangResource;
use App\Models\Barang;
use App\Models\StokBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BarangController extends Controller
{
    public function index()
    {
        return DB::transaction(function() {
            $barang = Barang::orderBy("created_at", "DESC")->get();

            foreach ($barang as $item) {

                $masuk = StokBarang::where("barang_id", $item->id)
                    ->where("status", 1)
                    ->sum("qty");

                $keluar = StokBarang::where("barang_id", $item->id)
                    ->where("status", 0)
                    ->sum("qty");

                $stok = $masuk - $keluar;

                $item->stok = $stok;
            }

            return GetBarangResource::collection($barang);
        });
    }

    public function store(Request $request)
    {
        return DB::transaction(function() use ($request) {

            $foto = $request->file("foto")->store("foto_barang");

            Barang::create([
                "name" => $request->name,
                "description" => $request->description,
                "harga" => $request->harga,
                "foto" => url('/storage/'.$foto)
            ]);

            return response()->json([
                "status" => true,
                "pesan" => "Data Berhasil di Tambahkan"
            ]);
        });
    }

    public function show($id)
    {
        return DB::transaction(function() use ($id) {
            $barang = Barang::where("id", $id)->first();

            return new GetBarangResource($barang);
        });
    }

    public function update(Request $request, $id)
    {
        return DB::transaction(function() use ($request, $id) {

            if ($request->file("foto")) {
                if ($request->gambarLama) {
                    Storage::delete($request->gambarLama);
                }
                
                $foto = $request->file("foto")->store("foto_barang");
            } else {
                $foto = $request->gambarLama;
            }

            Barang::where("id", $id)->update([
                "name" => $request->name,
                "description" => $request->description,
                "foto" => $foto
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
            $barang = Barang::where("id", $id)->first();

            Storage::delete($barang->foto);

            $barang->delete();

            return response()->json([
                "status" => true,
                "pesan" => "Data Berhasil di Hapus"
            ]);
        });
    }
}
