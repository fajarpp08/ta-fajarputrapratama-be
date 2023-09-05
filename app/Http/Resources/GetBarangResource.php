<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GetBarangResource extends JsonResource
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
            "nama" => $this->name,
            "deskripsi" => $this->description,
            "harga" => "Rp. " . number_format($this->harga),
            "foto" => $this->foto,
            "stok" => $this->stok
        ];
    }
}
