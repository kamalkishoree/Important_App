<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Config;

class AgentDocs extends Model
{
    //

    protected $fillable = ['agent_id','file_name','file_type','label_name'];
    protected $appends = ['image_url'];
   
    public function getImageUrlAttribute(){
        $secret = '';
        $server = 'http://192.168.100.211:8888';
        //$new    = \Thumbor\Url\Builder::construct($server, $secret, 'http://images.example.com/llamas.jpg')->fitIn(90,50);
        return    \Storage::disk("s3")->url($this->file_name);
        ///return $new; 
      }
}
