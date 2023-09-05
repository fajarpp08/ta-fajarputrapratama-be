<?php

namespace App\Http\Controllers;

use App\Http\Requests\BankRequest;
use App\Http\Resources\PaginationResource;
use App\Models\Bank;
use Illuminate\Http\Request;

class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $limit = $request->limit;
        $name = $request->name;
        $orderCol = $request->order_col ? $request->order_col :'id';
        $orderType= $request->order_type ? $request->order_type :'asc';

        $bank= Bank::where(function($f) use ($name) {
            if ($name && $name!='' && $name!='null'){
                $f->where('name', 'LIKE', '%' . $name . '%');
            }
        })

        ->orderBy($orderCol,$orderType)->paginate($limit);

        $data['paging'] = new PaginationResource($bank);
        
        $data['records'] = $bank->items();

        return $this->success($data,'get records data success');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BankRequest $request)
    {
        $bank = new Bank();
        $bank->bank = $request->bank;
        $bank->name = $request->name;
        $bank->norek = $request->norek;
        $bank->save();
        return $this->success($bank,'save data success');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $bank = Bank::findOrFail($id);
        return $this->success($bank,'get record success');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BankRequest $request, $id)
    {

        $bank = Bank::findOrFail($id);
        $bank->bank = $request->bank;
        $bank->name = $request->name;
        $bank->norek = $request->norek;
        $bank->save();
        return $this->success($bank,'update data success');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Bank::findOrFail($id)->delete();
        return $this->success(null,'delete data success');
    }
}
