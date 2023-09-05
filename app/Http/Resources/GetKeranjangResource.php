<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GetKeranjangResource extends JsonResource
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
            "user" => [
                "nama" => $this->user->name,
                "email" => $this->user->email  
            ],
            "tanggal" => Carbon::createFromFormat('Y-m-d', $this->tanggal)->isoFormat('D MMMM Y'),
            "jumlah_harga" => "Rp." . number_format($this->jumlah_harga),
            "status" => $this->status
        ];
    }
}
