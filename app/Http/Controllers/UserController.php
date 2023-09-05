<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\PaginationResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //pemakaian middleware bisa pakai cara ini
    // public function __construct()
    // {
    //     $this->middleware('superadminOnly')->only('store','delete','update');
    //    // $this->middleware('superadminOnly')->except('index');
    // }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request )
    {
        $limit = $request->limit;
        $name = $request->name;
        $email = $request->email;
        $role_id = $request->role_id;
        $orderCol = $request->order_col ? $request->order_col :'id';
        $orderType= $request->order_type ? $request->order_type :'asc';


        $user= User::with('role')->where(function($f) use ($name,$email) {
            if ($name && $name!='' && $name!='null'){
                $f->where('name', 'LIKE', '%' . $name . '%');
            }
            if ($email && $email!='' && $email!='null'){
                $f->where('email', 'LIKE', '%' . $email . '%');
            }
        })->where(function($f) use ($role_id) {
            if ($role_id && $role_id!='' && $role_id!='null'){
                $f->where('role_id', '=', $role_id);
            }
        })

        ->orderBy($orderCol,$orderType)->paginate($limit);

       $data['paging'] = new PaginationResource($user);
       $data['records'] = $user->items();
       //jika column di mapping pakai resource
       //$data['records'] = UserResource::collection($user);


        return $this->success($data,'get records data success');
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
    public function store(UserRequest $request)
    {

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role_id = $request->role_id;
        $user->password = Hash::make($request->password);
        $user->email_verified_at = Carbon::now();
        $user->save();


        return $this->success($user,'save data success');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
         $user = User::findOrFail($id);
         return $this->success($user,'get record success');

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
    public function update(UserRequest $request, $id)
    {

        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role_id = $request->role_id;
        if($request->password){
            $user->password = Hash::make($request->password);
        }
        $user->save();
        return $this->success($user,'update data success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return $this->success(null,'delete data success');
    }
}
