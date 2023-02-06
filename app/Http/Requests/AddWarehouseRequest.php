<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class AddWarehouseRequest extends FormRequest
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
        if(!checkTableExists('warehouses')){
            return [];
        }
        return [
            'name' => 'required',
            'code' => 'required|unique:warehouses,code,'. $request->warehouse_id,
            'address' => 'required',
            'category' => 'required',
        ];
   
    }
     public function messages(){
        return [
            "name.required" => __('The name field is required.'),
            "code.required" => __("The code field is required."),
            "address.required" => __("The address field is required."),
            "category.required" => __("The category field is required.")
        ];
    }
}
