<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GetCheckoutResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "invoice" => $this->invoice,
            "user" => [
                "nama" => $this->nama,
                "email" => $this->email,
                "alamat" => $this->alamat,
            ],
            "tanggal" => Carbon::createFromFormat("Y-m-d H:i:s", $this->tanggal)->isoFormat("dddd, DD MMMM YYYY | HH:mm:ss"),
            "jumlah_harga" => "Rp." . number_format($this->jumlah_harga*2),
            "status" => $this->status == 0 ? "Belum Melakukan Pembayaran" : "Sudah Melakukan Pembayaran"
        ];
    }
}
