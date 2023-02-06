<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class AddWarehouseManagerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(){
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request){
        return [
            'name' => 'required',
            'email' => 'required|unique:warehouse_managers,email,'. $request->warehouse_managers_id,
            'phone_number' => 'required',
            'warehouses' => 'required',
        ];

    }
     public function messages(){
        return [
            "name.required" => __('The name field is required.'),
            "email.required" => __("The email field is required."),
            "phone_number.required" => __("The phone number field is required."),
            "warehouses.required" => __("The warehouse field is required.")
        ];
    }
}
