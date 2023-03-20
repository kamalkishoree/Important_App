<?php

namespace App\Traits;

use App\Model\{OrderRatingQuestions,RatingTypes,FormAttribute};
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

trait RatingTrait
{

    public function getRatingType($request, $orderId)
    {
        return RatingTypes::with(['orderRating'=>function($q) use ($orderId) {
            $q->where('order_id',$orderId);
        }])->get();
    }
   
}
