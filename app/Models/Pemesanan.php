<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Pemesanan extends Model
{
    use HasFactory;
    protected $table = "pemesanans";
    protected $fillable=['nomor','jk','name','phone','time_from','time_to','total','harga'];
    protected $guarded=[];
    
    public function barang(){
        return $this->belongsTo(Barang::class);
    }

    protected static function booted()
    {
        static::creating(function($pemesanans) {
            $pemesanans->nomor = static::generateNomor();
        });
    }

    protected static function generateNomor()
    {
        return 'JAS-' . rand(1, 999999);
    }
}
