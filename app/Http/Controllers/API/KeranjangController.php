<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\GetKeranjangDetailResource;
use App\Http\Resources\GetKeranjangResource;
use App\Models\Barang;
use App\Models\Keranjang;
use App\Models\KeranjangDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KeranjangController extends Controller
{
    public function index()
    {
        return DB::transaction(function() {
            if (empty(Auth::user()->id)) {
                return response()->json(["pesan" => "Konsumen Tidak Ditemukan"]);
            } else {
                $keranjang = Keranjang::where("user_id", Auth::user()->id)
                ->where("status", 0)
                ->first();
                
                return new GetKeranjangResource($keranjang);
            }
        });
    }
    
    public function store(Request $request)
    {
        return DB::transaction(function() use ($request) {
            if (empty(Auth::user())) {
                return response()->json([
                    "status" => false,
                    "pesan" => "User Tidak Ditemukan" 
                ]);
            } else {
                $produk = Barang::where("id", $request->barang_id)->first();
                
                $cek_pesanan = Keranjang::where("user_id", Auth::user()->id)->first();
                
                if (empty($cek_pesanan)) {
                    $keranjang = Keranjang::create([
                        "user_id" => Auth::user()->id,
                        "tanggal" => date("Y-m-d"),
                        "jumlah_harga" => 0,
                        "status" => 0 
                    ]);
                }
                
                $pesanan_baru = Keranjang::where("user_id", Auth::user()->id)->first();
                
                $cek_pesanan_detail = KeranjangDetail::where("barang_id", $request->barang_id)
                ->where("keranjang_id", $pesanan_baru->id)
                ->first();
                
                if (empty($cek_pesanan_detail)) {
                    $detail_keranjang = KeranjangDetail::create([
                        "produk_id" => $request->produk_id,
                        "barang_id" => $request->barang_id,
                        "keranjang_id" => $pesanan_baru->id,
                        "jumlah" => 1,
                        "jumlah_harga" => $produk->harga * 1
                    ]);
                } else {
                    $pesanan_detail = KeranjangDetail::where("barang_id", $request->barang_id)
                    ->where("keranjang_id", $pesanan_baru->id)
                    ->first();
                    
                    $pesanan_detail->jumlah = $pesanan_detail->jumlah + 1;
                    
                    $baru = $produk->harga * 1;
                    
                    $pesanan_detail->jumlah_harga = $pesanan_detail->jumlah_harga + $baru;
                    $pesanan_detail->update();
                }
                
                $data_keranjang = Keranjang::where("user_id", Auth::user()->id)
                ->first();
                
                $data_keranjang->jumlah_harga = $data_keranjang->jumlah_harga + $produk->harga * 1;
                
                $data_keranjang->update();
                
                return response()->json([
                    "status" => true, 
                    "pesan" => "Data Berhasil di Tambahkan"
                ]);
            }
        });
    }

    public function show($id_keranjang)
    {
        return DB::transaction(function() use ($id_keranjang) {
            $data = KeranjangDetail::where("keranjang_id", $id_keranjang)
                ->orderBy("created_at", "DESC")
                ->get();

            return GetKeranjangDetailResource::collection($data);
        });
    }

    public function destroy($id_keranjang)
    {
        return DB::transaction(function() use ($id_keranjang) {
            $keranjang = Keranjang::where("id", $id_keranjang)->first();

            $keranjang_detail = KeranjangDetail::where("keranjang_id", $keranjang->id)->get();

            foreach ($keranjang_detail as $item) {
                $item->delete();
            }

            $keranjang->delete();

            return response()->json([
                "status" => true,
                "pesan" => "Data Berhasil di Hapus"
            ]);
        });
    }

    public function tambah($id_keranjang_detail)
    {
        return DB::transaction(function() use ($id_keranjang_detail) {
            $keranjang_detail = KeranjangDetail::where("id", $id_keranjang_detail)->first();
            
            $barang = Barang::where("id", $keranjang_detail->barang_id)->first();

            $keranjang = Keranjang::where("id", $keranjang_detail->keranjang_id)->first();

            $keranjang->update([
                "jumlah_harga" => $keranjang->jumlah_harga + $barang->harga
            ]);

            $keranjang_detail->update([
                "jumlah" => $keranjang_detail->jumlah + 1,
                "jumlah_harga" => $keranjang_detail->jumlah_harga + $barang->harga
            ]);

            return response()->json([
                "status" => true,
                "pesan" => "Data Berhasil di Simpan"
            ]);
        });
    }

    public function kurang($id_keranjang_detail)
    {
        return DB::transaction(function() use ($id_keranjang_detail) {

            $keranjang_detail = KeranjangDetail::where("id", $id_keranjang_detail)->first();
            
            $barang = Barang::where("id", $keranjang_detail->barang_id)->first();

            $keranjang = Keranjang::where("id", $keranjang_detail->keranjang_id)->first();

            $keranjang->update([
                "jumlah_harga" => $keranjang->jumlah_harga - $barang->harga
            ]);

            $keranjang_detail->update([
                "jumlah" => $keranjang_detail->jumlah - 1,
                "jumlah_harga" => $keranjang_detail->jumlah_harga - $barang->harga
            ]);

            if ($keranjang_detail->jumlah == 0) {
                $keranjang_detail->delete();
            }

            if ($keranjang->jumlah_harga == 0) {
                $keranjang->delete();
            }

            return response()->json([
                "status" => true,
                "pesan" => "Data Berhasil di Simpan"
            ]);
        });
    }

    public function hapus($id_keranjang_detail)
    {
        return DB::transaction(function() use ($id_keranjang_detail) {

            $keranjang_detail = KeranjangDetail::where("id", $id_keranjang_detail)->first();

            $keranjang = Keranjang::where("id", $keranjang_detail->keranjang_id)->first();

            $keranjang->update([
                "jumlah_harga" => $keranjang->jumlah_harga - $keranjang_detail->jumlah_harga
            ]);
            
            $keranjang_detail->delete();

            if ($keranjang->jumlah_harga == 0) {
                $keranjang->delete();
            }

            return response()->json([
                "status" => true,
                "pesan" => "Data Berhasil di Hapus"
            ]);
        });
    }
}
