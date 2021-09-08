<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Config;

class AgentDocs extends Model
{
    //

    protected $table = 'agent_docs';
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['agent_id','file_type','file_name'];
    public function getfileNameAttribute($value)
    {
        $values = array();
        if (!empty($value)) {
            $img = $value;
        }
        $values['proxy_url'] = \Config::get('app.IMG_URL1');
        $values['image_path'] = \Config::get('app.IMG_URL2') . '/' . \Storage::disk('s3')->url($img);
        $values['image_fit'] = \Config::get('app.FIT_URl');
        $values['storage_url'] = \Storage::disk('s3')->url($img);
        return $values;
    }
}
