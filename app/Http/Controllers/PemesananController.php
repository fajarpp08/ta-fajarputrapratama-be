<?php

namespace App\Http\Controllers;

use App\Http\Requests\PemesananRequest;
use App\Http\Resources\GetOptionsResource;
use Illuminate\Http\Request;
use App\Models\Pemesanan;
use App\Http\Resources\PaginationResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PemesananController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $limit = $request->limit;
        $name = $request->name;
        $barang_id = $request->barang_id;
        $orderCol = $request->order_col ? $request->order_col : 'id';
        $orderType = $request->order_type ? $request->order_type : 'asc';


        $pemesanan = Pemesanan::with('barang')->where(function ($f) use ($name) {
            if ($name && $name != '' && $name != 'null') {
                $f->where('name', 'LIKE', '%' . $name . '%');
            }
        })->where(function ($f) use ($barang_id) {
            if ($barang_id && $barang_id != '' && $barang_id != 'null') {
                $f->where('barang_id', '=', $barang_id);
            }
        })

            ->orderBy($orderCol, $orderType)->paginate($limit);


        $data['paging'] = new PaginationResource($pemesanan);
        $data['records'] = $pemesanan->items();
        //jika column di mapping pakai resource
        //$data['records'] = UserResource::collection($user);


        return $this->success($data, 'get records data success');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PemesananRequest $request)
    {
        $checkinDate = $request->input('time_from');
        $checkoutDate = $request->input('time_to');
        $price = $request->input('harga');

        $startDate = \Carbon\Carbon::createFromFormat('Y-m-d', $checkinDate);
        $endDate = \Carbon\Carbon::createFromFormat('Y-m-d', $checkoutDate);
        $numberOfDays = $endDate->diffInDays($startDate);

        $totalPrice = $price * $numberOfDays;
        $pemesanan = new Pemesanan([
            'time_from' => $checkinDate,
            'time_to' => $checkoutDate,
            'total' => $totalPrice,
            'harga' => $price,
        ]);
        $pemesanan->nomor = $request->nomor;
        $pemesanan->jk = $request->jk;
        $pemesanan->name = $request->name;
        $pemesanan->phone = $request->phone;
        $pemesanan->barang_id = $request->barang_id;

        // $pemesanan->user_id = Auth::user()->id;

        $pemesanan->save();

        // $dataPemesanan = Pemesanan::with(['mobil'])->findOrFail($pemesanan->pesanable);

        return $this->success($pemesanan, 'save data success');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PemesananRequest $request, $id)
    {
        $pemesanan = Pemesanan::findOrFail($id);
        $pemesanan->nomor = $request->nomor;
        $pemesanan->jk = $request->jk;
        $pemesanan->name = $request->name;
        $pemesanan->phone = $request->phone;
        $pemesanan->barang_id = $request->barang_id;
        $pemesanan->save();
        return $this->success($pemesanan, 'update data success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Pemesanan::findOrFail($id)->delete();
        return $this->success(null, 'delete data success');
    }

    function hitungTotalHari($pemesanan)
    {
        $totalHari = 0;
        foreach ($pemesanan as $reservasi) {
            $checkIn = Carbon::parse($reservasi['time_from']);
            $checkOut = Carbon::parse($reservasi['time_from']);
            $selisih = $checkOut->diffInDays($checkIn);
            $totalHari += $selisih;
        }
        return $totalHari;
    }

    public function GetOptionsResource(Request $request)
    {
        $name = $request->name;
        $barangId =  $request->barang_id;
        $orderCol = $request->order_col ? $request->order_col : 'id';
        $orderType = $request->order_type ? $request->order_type : 'asc';


        $pemesanan = Pemesanan::where(function ($f) use ($name, $barangId) {
            if ($name && $name != '' && $name != 'null') {
                $f->where('name', 'LIKE', '%' . $name . '%');
            }
            if ($barangId && $barangId != '' && $barangId != 'null') {
                $f->where('barang_id', '=', $barangId);
            }
        })

            ->orderBy($orderCol, $orderType)->get();

        $data['records'] = GetOptionsResource::collection($pemesanan);


        return $this->success($data, 'get records data success');
    }
}
