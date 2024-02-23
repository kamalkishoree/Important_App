<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class VendorMedia extends Model
{
	protected $table = 'vendor_media';
  protected $fillable = ['media_type','vendor_id','path'];

    public function getPathAttribute($value)
    {
      $values = array();
      $img = 'default/default_image.png';
      $values['db_image'] = '';
      if(!empty($value)){
        $img = $value;
        $values['db_image'] = $img;
      }
      $img = str_replace(' ', '', $img);
      $ex = checkImageExtension($img);
      $values['proxy_url'] = \Config::get('app.IMG_URL1');
      if (substr($img, 0, 7) == "http://" || substr($img, 0, 8) == "https://"){
        $values['image_path'] = \Config::get('app.IMG_URL2').'/'.$img;
      } else {
        $values['image_path'] = \Config::get('app.IMG_URL2').'/'.\Storage::disk('s3')->url($img).$ex;
      }
      $values['image_fit'] = \Config::get('app.FIT_URl');
      $values['original_image'] = \Storage::disk('s3')->url($img);
      return $values;
    }
}
