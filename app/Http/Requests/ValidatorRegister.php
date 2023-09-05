<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

class ValidatorRegister extends FormRequest
{
    public function rules(Request $request)
    {
        return [
            "name" => "required",
            "email" => "required",
            "password" => "required|min:8|max:15"
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
