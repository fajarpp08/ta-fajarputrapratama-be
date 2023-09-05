<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeranjangDetail extends Model
{
    use HasFactory;

    protected $table = "keranjang_detail";

    protected $guarded = [''];

    public function keranjang()
    {
        return $this->belongsTo("App\Models\Keranjang", "keranjang_id", "id");
    }

    public function barang()
    {
        return $this->belongsTo("App\Models\Barang", "barang_id", "id");
    }
}
