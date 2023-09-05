<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\GetCheckoutResource;
use App\Http\Resources\GetDetailCheckoutResource;
use App\Models\Pesan;
use App\Models\PesanDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RiwayatKonsumenController extends Controller
{
    public function index()
    {
        return DB::transaction(function() {
            $data = Pesan::orderBy("created_at", "DESC")
                ->orderBy("created_at", "ASC")
                ->get();

            return GetCheckoutResource::collection($data);
        });
    }

    public function show($id_checkout)
    {
        return DB::transaction(function() use ($id_checkout) {
            $data = PesanDetail::where("pesan_id", $id_checkout)->orderBy("created_at", "ASC")->get();

            return GetDetailCheckoutResource::collection($data);
        });
    }
}
