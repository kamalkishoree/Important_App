<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OrderRatingQuestions extends Model
{
    protected $fillable = ['order_id', 'question_id', 'option_id','question_name','option_value'];
}
