<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ValidatorCheckout;
use App\Http\Resources\GetCheckoutResource;
use App\Http\Resources\GetDetailCheckoutResource;
use App\Models\Keranjang;
use App\Models\KeranjangDetail;
use App\Models\Pesan;
use App\Models\PesanDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PesanController extends Controller
{
    public function index()
    {
        return DB::transaction(function() {
            $checkout = Pesan::where("user_id", Auth::user()->id)
                ->orderBy("created_at", "ASC")
                ->get();

            return GetCheckoutResource::collection($checkout);
        });
    }

    public function store(ValidatorCheckout $request)
    {
        return DB::transaction(function() use ($request) {
            $keranjang = Keranjang::where("user_id", Auth::user()->id)->first();

            $detail = KeranjangDetail::where("keranjang_id", $keranjang->id)->get();

            $pesan = Pesan::create([
                "user_id" => Auth::user()->id,
                "invoice" => "TRN-" . date("YmdHis"),
                "nama" => Auth::user()->name,
                "email" => Auth::user()->email,
                "alamat" => $request->alamat,
                "tanggal" => date("Y-m-d H:i:s"),
                "jumlah_harga" => $keranjang->jumlah_harga,
                "status" => 0
            ]);
            
            foreach ($detail as $n) {
                PesanDetail::create([
                    "pesan_id" => $pesan->id,
                    "nama_barang" => $n->barang->name,
                    "foto" => $n->barang->foto,
                    "deskripsi" => $n->barang->description,
                    "harga" => $n->barang->harga,
                    "jumlah" => $n->jumlah,
                    "jumlah_harga" => $n->jumlah_harga
                ]);
                
                $n->delete();
            }

            $keranjang->delete();

            return response()->json([
                "status" => true,
                "pesan" => "Data Berhasil di Tambahkan"
            ]);
        });
    }

    public function show($id_pesan)
    {
        return DB::transaction(function() use ($id_pesan) {
            $detail = PesanDetail::where("pesan_id", $id_pesan)
                ->orderBy("created_at", "ASC")
                ->get();

            return GetDetailCheckoutResource::collection($detail);
        });
    }
}
