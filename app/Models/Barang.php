<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Barang extends Model
{
    use HasFactory;

    protected $guarded = [''];

    public function kategori(){
        return $this->belongsTo(Kategori::class);
    }
    public function images(): MorphMany
    {
        return $this->morphMany(ImageFile::class, 'imageable');
    }
}
