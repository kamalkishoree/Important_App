<?php

namespace App\Traits;

use App\Model\FormAttribute;
use App\Model\OrderFormAttribute;
use DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

trait FormAttributeTrait
{


    function getAttributeForm($request, $id = 0)
    {

        $formAttributes = [];
        if (checkTableExists('form_attributes')) {
            // , 'varcategory.cate.primary'
            $formAttributes = FormAttribute::with('option')
                ->select('form_attributes.*')

                ->where('form_attributes.status', '!=', 2)
                ->orderBy('position', 'asc')->get();
        }
        return $formAttributes;
    }

    public function saveOrderFormAttribute($request, $orderId)
    {
        // $attribute = json_decode($request->attribute_data, true);
        $attribute = $request->attribute_data;
        \Log::info('attribute');
        \Log::info($attribute);
        $insert_arr = [];
        $insert_count = 0;
        foreach ($attribute as $key => $value) {
            if (!empty($value) && !empty($value['option'] && is_array($value))) {

                if (!empty($value['type']) && $value['type'] == 1) { // dropdown
                    $value_arr = @$value['value'];

                    foreach ($value['option'] as $key1 => $val1) {
                        if (@in_array($val1['option_id'], $value_arr)) {
                            $insert_arr[$insert_count]['order_id'] = $orderId->order_id;
                            $insert_arr[$insert_count]['attribute_id'] = $value['id'];
                            $insert_arr[$insert_count]['key_name'] = $value['attribute_title'];
                            $insert_arr[$insert_count]['attribute_option_id'] = $val1['option_id'];
                            $insert_arr[$insert_count]['key_value'] = $val1['option_id'];
                            $insert_arr[$insert_count]['is_active'] = 1;
                        }
                        $insert_count++;
                    }

                } else {
                    $value_arr = @$value['value'];

                    // \Log::info($option['option_id']);
                    foreach ($value['option'] as $option_key => $option) {
                        if (!empty($value['type']) && $value['type'] == 4) { // textbox
                            $insert_arr[$insert_count]['order_id'] = $orderId->order_id;
                            $insert_arr[$insert_count]['attribute_id'] = $value['id'];
                            $insert_arr[$insert_count]['key_name'] = $value['attribute_title'];
                            $insert_arr[$insert_count]['attribute_option_id'] = $option['option_id'];
                            $insert_arr[$insert_count]['key_value'] = (!empty($value['value']) && !empty($value['value'][0]) ? $value['value'][0] : '');
                            $insert_arr[$insert_count]['is_active'] = 1;
                        }elseif(!empty($value['type']) && $value['type'] == 6) {
                            $image = '';

                            if (isset($request->file) && count($request->file) > 0) {
                                $folder = str_pad(Auth::user()->id, 8, '0', STR_PAD_LEFT);
                                $folder = 'client_' . $folder;
                                $value = $value['value'];
                                    $file = $value;
                                    $file_name = uniqid() . '.' .  $file->getClientOriginalExtension();
                                    $s3filePath = '/assets/' . $folder . '/' . $file_name;
                                    $image = Storage::disk('s3')->put($s3filePath, $file, 'public');
                            }
                            $insert_arr[$insert_count]['order_id'] = $orderId->order_id;
                            $insert_arr[$insert_count]['attribute_id'] = $value['id'];
                            $insert_arr[$insert_count]['key_name'] = $value['attribute_title'];
                            $insert_arr[$insert_count]['attribute_option_id'] = $option['option_id'];
                            $insert_arr[$insert_count]['key_value'] = $image;
                            $insert_arr[$insert_count]['is_active'] = 1;

                        } elseif (@in_array($option['option_id'], $value_arr)) {

                            $insert_arr[$insert_count]['order_id'] = $orderId->order_id;
                            $insert_arr[$insert_count]['attribute_id'] = $value['id'];
                            $insert_arr[$insert_count]['key_name'] = $value['attribute_title'];
                            $insert_arr[$insert_count]['attribute_option_id'] = $option['option_id'];
                            $insert_arr[$insert_count]['key_value'] = $option['option_id'];
                            $insert_arr[$insert_count]['is_active'] = 1;
                        }

                        $insert_count++;
                    }
                }
            }
        }
        \Log::info('insert_arr');
        \Log::info($insert_arr);
        if (!empty($insert_arr)) {
            OrderFormAttribute::insert($insert_arr);
        }
        return true;
    }
}
