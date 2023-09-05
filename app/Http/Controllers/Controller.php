<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

     public function success($data,$message): JsonResponse
    {
        $respon['respon_status'] = array('status' => 'SUCCESS', 'code' => 200, 'message' => $message);
        if ($data!=null) {
            $respon['data'] = $data;
        }
        return response()->json($respon,200);
    }
    public function error($error_status,$message,$code,$errors = array()): JsonResponse
    {
        $respon['respon_status'] = array('status' => $error_status, 'code' =>  $code, 'message' => $message);
        $respon['errors'] = $errors;
        return response()->json($respon, $code);
    }
}
