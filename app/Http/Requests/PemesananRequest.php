<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validations\Validator;
use Illuminate\Http\Request;

class PemesananRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules() : array
    {
        return [
            'jk' => 'required',
            'name' => 'string|required|min:3',
            'phone' => 'required|numeric',
            'time_from' => 'required|date',
            'time_to' => 'required|date',
            'harga' => 'required|numeric',
            'barang_id' => 'required',
    ];
    }
}
