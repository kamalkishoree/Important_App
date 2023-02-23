<?php

namespace App\Http\Controllers;

use App\Model\FormAttribute;
use App\Model\FormAttributeOption;
use App\Model\FormAttributeOptionTranslation;
use App\Model\FormAttributeTranslation;
use Illuminate\Http\Request;

class FormAttributeController extends Controller
{
    public function create(Request $request)
    {
        $returnHTML = view('attributes.add-attribute')->render();
        return response()->json(array('success' => true, 'html' => $returnHTML));
    }

    public function store(Request $request)
    {
        
        
       
        $variant = new FormAttribute();
        $variant->title = (!empty($request->title[0])) ? $request->title[0] : '';
        $variant->type = $request->type;
        $variant->position = 1;
        $variant->save();
        $data = $data_cate = array();
        if($variant->id > 0){
            foreach ($request->title as $key => $value) {
                $varTrans = new FormAttributeTranslation();
                $varTrans->title = $request->title[$key];
                $varTrans->attribute_id = $variant->id;
                $varTrans->language_id = $request->language_id[$key];
                $varTrans->save();
            }

            foreach ($request->hexacode as $key => $value) {

                $varOpt = new FormAttributeOption();
                $varOpt->title = $request->opt_color[0][$key];
                $varOpt->attribute_id = $variant->id;
                $varOpt->hexacode = ($value == '') ? '' : $value;
                $varOpt->save();

                foreach($request->language_id as $k => $v) {
                    $data[] = [
                        'title' => $request->opt_color[$k][$key],
                        'attribute_option_id' => $varOpt->id,
                        'language_id' => $v
                    ];
                }
            }
            FormAttributeOptionTranslation::insert($data);
        }
        return redirect()->back()->with('success', 'Attribute added successfully!');
        
    }
}
