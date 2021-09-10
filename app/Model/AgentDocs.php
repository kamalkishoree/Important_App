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


    protected $fillable = ['agent_id','file_name','file_type'];
   
}
