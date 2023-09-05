<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\StokBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StokBarangController extends Controller
{
    public function masuk(Request $request)
    {
        return DB::transaction(function() use ($request) {
            StokBarang::create([
                "barang_id" => $request->barang_id,
                "tanggal" => date("Y-m-d"),
                "qty" => $request->qty,
                "status" => 1
            ]);

            return response()->json([
                "status" => true,
                "pesan" => "Data Berhasil di Tambahkan"
            ]);
        });
    }

    public function keluar(Request $request)
    {
        return DB::transaction(function() use ($request) {
            StokBarang::create([
                "barang_id" => $request->barang_id,
                "tanggal" => date("Y-m-d"),
                "qty" => $request->qty,
                "status" => 0
            ]);

            return response()->json([
                "status" => true,
                "pesan" => "Data Berhasil di Tambahkan"
            ]);
        });
    }
}
