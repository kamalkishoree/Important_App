<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class OrderPanelDetail extends Model
{
    protected $fillable = ['name', 'url', 'code', 'key', 'status', 'last_sync'];

    // Mutator for DB Password column
    // when "db password" will save, it will convert into encrypted
    public function setDbPasswordAttribute($dbPassword)
    {
        $this->attributes['db_password'] = Crypt::encryptString($dbPassword);
    }

    public function getDbPasswordAttribute($dbPassword)
    {
        return Crypt::decryptString($dbPassword);
    }
}
