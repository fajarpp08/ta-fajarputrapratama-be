<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

class ValidatorCheckout extends FormRequest
{
    public function rules(Request $request)
    {
        return [
            "alamat" => "required",
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            "success" => false,
            "message" => "Maaf, Terjadi Kesalahan Dalam Permintaan Request",
            "data" => $validator->errors()
        ]));
    }
}
