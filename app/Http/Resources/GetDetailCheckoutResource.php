<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GetDetailCheckoutResource extends JsonResource
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
            "barang" => [
                "nama" => $this->nama_barang,
                "foto" => $this->foto,
                "deskripsi" => $this->deskripsi,
                "harga" => "Rp. " . number_format($this->harga), 
            ],
            "qty" => $this->jumlah,
            "total_beli" => "Rp. ". number_format($this->jumlah_harga)
        ];
    }
}
