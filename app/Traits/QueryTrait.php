<?php
namespace App\Traits;

trait QueryTrait{
    
    public function betweenFilter($query,$field,$offset,$date_from,$date_to)
    {
       return $query->whereRaw("CONVERT_TZ($field,'+00:00','".$offset."') between '".$date_from."' and '".$date_to."'");
    } 
}
