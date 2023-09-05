<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class BaseApiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }



     /**
     * If validator fails return the exception in json form
     * @param Validator $validator
     * @return array
     */
    protected function failedValidation(Validator $validator)
    {
        $respon['respon_status'] = array('status' => 'ERROR.INVALID_ENTITY', 'code' =>  422, 'message' => 'Data isian tidak valid');
        $respon['errors'] = $validator->errors();

        throw new HttpResponseException(response()->json($respon, 422));
    }
    protected function failedAuthorization()
    {
        $respon['respon_status'] = array('status' => 'ERROR.UNAUTHORIZED', 'code' =>  403, 'message' => 'this action unauthorized');

        throw new HttpResponseException(response()->json($respon, 422));
    }

   // abstract public function rules();

}
