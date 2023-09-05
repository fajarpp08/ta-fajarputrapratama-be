<?php

use App\Http\Controllers\API\BarangController as APIBarangController;
use App\Http\Controllers\API\KategoriBarangController;
use App\Http\Controllers\API\KategoriController as APIKategoriController;
use App\Http\Controllers\API\KeranjangController;
use App\Http\Controllers\API\PembayaranController as APIPembayaranController;
use App\Http\Controllers\API\PesanController;
use App\Http\Controllers\API\RiwayatKonsumenController;
use App\Http\Controllers\API\StokBarangController;
use App\Http\Controllers\API\UserController as APIUserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ApiAuthController;
use App\Http\Controllers\AutentikasiController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PemesananController;
use App\Http\Controllers\PembayaranController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/



Route::prefix("login")->group(function() {
    Route::post("/", [AutentikasiController::class, "login"]);
    Route::get("/", [AutentikasiController::class, "index"])->name("login");
});

Route::prefix("register")->group(function() {
    Route::post("/", [AutentikasiController::class, "register"]);
});

// Route::resource('barang', [APIBarangController::class, "index"]);
// Route::resource('kategori', [APIKategoriController::class, 'index']);

Route::resource('barang', APIBarangController::class);
Route::resource("kategori", APIKategoriController::class); 

// kodingan tambahan get barang

Route::middleware("auth:sanctum")->group(function() {
    // Route::resource('barang', APIBarangController::class);
    // Route::resource("kategori", APIKategoriController::class);
    // Route::resource("kategori_barang", KategoriBarangController::class);
    Route::resource("keranjang", KeranjangController::class);
    Route::resource("bank", BankController::class);

    
    Route::post("/user", [APIUserController::class, "store"]);

    Route::get("/user/admin", [APIUserController::class, "index"]);
    Route::post("/user/admin", [APIUserController::class, "store_admin"]);
    Route::get("/user/admin/{id}/detail", [APIUserController::class, "show"]);
    Route::put("/user/admin/{id}/update", [APIUserController::class, "update"]);
    Route::delete("/user/admin/{id}/delete", [APIUserController::class, "delete"]);

    Route::prefix("stok_barang")->group(function() {
        Route::post("/masuk", [StokBarangController::class, "masuk"]);
        Route::post("/keluar", [StokBarangController::class, "keluar"]);
    });

    Route::prefix("cart")->group(function() {
        Route::post("/tambah/{id_keranjang_detail}", [KeranjangController::class, "tambah"]);
        Route::post("/kurang/{id_keranjang_detail}", [KeranjangController::class, "kurang"]);
        Route::delete("hapus/{id_keranjang_detail}", [KeranjangController::class, "hapus"]);
    });

    Route::resource("checkout", PesanController::class);
    
    Route::prefix("pembayaran")->group(function() {
        Route::post("/{id_pesanan}", [APIPembayaranController::class, "store"]);
    });

    Route::resource("riwayat", RiwayatKonsumenController::class);

    Route::get("/logout", [AutentikasiController::class, "logout"]);
});

// Route::get('/barang', [BarangController::class, 'index']);
// Route::get('/kategori', [KategoriController::class, 'index']);
// Route::get('/kategori/getOption', [KategoriController::class, 'GetOptionsResource']);
// Route::get('/barang/getOption', [BarangController::class, 'GetOptionsResource']);
// Route::get('/pemesanan/getOption', [PemesananController::class, 'GetOptionsResource']);
// Route::apiResource('/barang', BarangController::class)->except(['store', 'destroy', 'update']);
// Route::apiResource('/kategori', KategoriController::class)->except(['store', 'destroy', 'update']);

// Route::post('register', [RegisterController::class, 'register']);
// Route::get('register/confirmation/{id}', [RegisterController::class, 'confirmation']);
// Route::post('login', [ApiAuthController::class, 'login']);

// Route::group(['middleware' => 'auth:api'], function () { // auth login di matiin dulu buat test
//     Route::post('logout', [ApiAuthController::class, 'logout']);
//     Route::get('cek-token', [ApiAuthController::class, 'cekValidToken']);
//     Route::group(['middleware' => 'superadminOnly'], function () {
//         Route::apiResource('/user', UserController::class);
//     });

//     Route::apiResource('/barang', BarangController::class)->only(['store', 'destroy', 'update']);
//     Route::apiResource('/kategori', KategoriController::class)->only(['store', 'destroy', 'update']);
//     Route::apiResource('/pemesanan', PemesananController::class);
//     Route::apiResource('/pembayaran', PembayaranController::class);
//     Route::apiResource('/bank', BankController::class);

// });




