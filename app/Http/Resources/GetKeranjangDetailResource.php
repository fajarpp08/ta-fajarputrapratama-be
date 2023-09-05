<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GetKeranjangDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id_keranjang_detail" => $this->id,
            "barang" => [
                "id" => $this->barang->id,
                "nama" => $this->barang->name,
                "deskripsi" => $this->barang->description,
                "harga" => "Rp. " . number_format($this->barang->harga),
                "foto" => $this->barang->foto
            ],
            "keranjang" => $this->keranjang->only("id"),
            "total_beli" => $this->jumlah,
            "total_harga" => "Rp. " . number_format($this->jumlah_harga)
        ];
    }
}
