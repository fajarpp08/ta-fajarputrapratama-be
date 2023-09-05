<?php

namespace App\Http\Controllers;

use App\Http\Requests\BarangRequest;
use App\Http\Resources\PaginationResource;
use App\Http\Resources\GetOptionsResource;
use App\Models\Barang;
use App\Models\ImageFile;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = $request->limit;
        $name = $request->name;
        $kategori_id = $request->kategori_id;
        $orderCol = $request->order_col ? $request->order_col : 'id';
        $orderType = $request->order_type ? $request->order_type : 'asc';


        $barang = Barang::with('kategori','images')->where(function ($f) use ($name) {
            if ($name && $name != '' && $name != 'null') {
                $f->where('name', 'LIKE', '%' . $name . '%');
            }
        })->where(function ($f) use ($kategori_id) {
            if ($kategori_id && $kategori_id != '' && $kategori_id != 'null') {
                $f->where('kategori_id', '=', $kategori_id);
            }
        })

            ->orderBy($orderCol, $orderType)->paginate($limit);


        $data['paging'] = new PaginationResource($barang);
        $data['records'] = $barang->items();
        //jika column di mapping pakai resource
        //$data['records'] = UserResource::collection($user);


        return $this->success($data, 'get records data success');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BarangRequest $request)
    {
        $fileImage  = null;
        if ($request->hasFile('image')) {
            $request->validate([
                'image' => 'required|mimes:jpg,jpeg,png,bmp |max:4048',
            ]);
            //save upload file
            $hash = Str::random(30);
            $extension = '.' . $request->file('image')->guessExtension();
            $fileSize = $request->file('image')->getSize();
            $filenameClient = $hash . $extension;
            $request->file('image')->storeAs('type/', $filenameClient, $disk = 'images');
            $file_path = Storage::disk('images')->path('type/', true) . '/' . $filenameClient;
            if (file_exists($file_path)) {
                $fileImage = 'images/type/' . $filenameClient;
            }
        }

        $barang = new Barang();
        $barang->name = $request->name;
        $barang->kategori_id = $request->kategori_id;
        $barang->description = $request->description;
        $barang->harga = $request->harga;
        // $harga = $request->harga;
        $barang->save();
        if ($fileImage) {
            $image = new ImageFile();
            $image->name = $filenameClient;
            $image->path = $fileImage;
            $image->size = $fileSize;
            $barang->images()->save($image);
        }
        $dataBarang = Barang::with(['images'])->findOrFail($barang->id);
        return $this->success($dataBarang,'save data success');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $barang = Barang::with(['kategori','images'])->findOrFail($id);
        return $this->success($barang,'get record success');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BarangRequest $request, $id)
    {

        $barang = Barang::findOrFail($id);
        $barang->name = $request->name;
        $barang->description = $request->description;
        $barang->harga = $request->harga;
        // $harga = $request->harga;
        $barang->kategori_id = $request->kategori_id;
        $barang->save();
        return $this->success($barang,'update data success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Barang::findOrFail($id)->delete();
        return $this->success(null,'delete data success');
    }

    public function GetOptionsResource(Request $request)
    {
        $name = $request->name;
        $kategoriId =  $request->kategori_id;
        $orderCol = $request->order_col ? $request->order_col :'id';
        $orderType= $request->order_type ? $request->order_type :'asc';


        $barang= Barang::where(function($f) use ($name,$kategoriId) {
            if ($name && $name!='' && $name!='null'){
                $f->where('name', 'LIKE', '%' . $name . '%');
            }
            if ($kategoriId && $kategoriId!='' && $kategoriId!='null'){
                $f->where('kategori_id', '=',$kategoriId);
            }
        })

        ->orderBy($orderCol,$orderType)->get();


        $data['records'] = GetOptionsResource::collection($barang);


        return $this->success($data,'get records data success');
    }
}
