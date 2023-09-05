<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\PembelianBarang;
use App\Models\Pesan;
use App\Models\PesanDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PembayaranController extends Controller
{
    public function store(Request $request, $id_pesanan)
    {
        return DB::transaction(function() use ($request, $id_pesanan) {

            $pesan = Pesan::where("id", $id_pesanan)->first();

            $detail = PesanDetail::where("pesan_id", $pesan->id)->get();

            $bukti = $request->file("bukti_pembayaran")->store("bukti_pembayaran");

            $pembayaran = Pembayaran::create([
                "user_id" => Auth::user()->id,
                "pesan_id" => $id_pesanan,
                "tanggal_pembelian" => date("Y-m-d H:i:s"),
                "total_pembelian" => $pesan->jumlah_harga,
                "status_pembelian" => "SUDAH PEMBAYARAN",
                "bukti_pembayaran" => url("/storage/".$bukti)
            ]);

            foreach ($detail as $item) {
                PembelianBarang::create([
                    "pembayaran_id" => $pembayaran->id,
                    "nama_barang" => $item->nama_barang,
                    "jumlah" => $item->jumlah,
                    "harga" => $item->jumlah_harga
                ]);
            }

            $pesan->update([
                "status" => 1
            ]);

            return response()->json([
                "status" => true,
                "pesan" => "Data Berhasil di Tambahkan"
            ]);
        });
    }
}
