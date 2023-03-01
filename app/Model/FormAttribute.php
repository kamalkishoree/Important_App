<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class FormAttribute extends Model
{

    protected $guarded = [];

    public function translation()
    {
        return $this->hasMany('App\Model\FormAttributeTranslation', 'attribute_id', 'id')->join('languages', 'form_attribute_translations.language_id', 'languages.id')->select('form_attribute_translations.id', 'form_attribute_translations.title', 'form_attribute_translations.attribute_id', 'form_attribute_translations.language_id', 'languages.name');
    }

    public function option()
    {
        return $this->hasMany('App\Model\FormAttributeOption', 'attribute_id', 'id');
    }

    public function translation_one()
    {
        $langset = 1;
        return $this->hasOne('App\Model\FormAttributeTranslation', 'attribute_id', 'id')->select('attribute_id', 'title')->where('language_id', $langset);
    }
    public  function orderQuetions()
    {
        return $this->hasOne('App\Model\OrderRatingQuestions', 'question_id', 'id'); 
    }
    public static function getFormAttribute($attribute_for)
    {
       
        return  FormAttribute::with(['option','translation_one'])
                        ->where('status', '!=', 2)
                        ->where('attribute_for', $attribute_for)
                        ->orderBy('position', 'asc')->get();
                
               
    }
}
