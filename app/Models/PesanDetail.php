<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesanDetail extends Model
{
    use HasFactory;

    protected $table = "pesan_detail";

    protected $guarded = [''];
}
