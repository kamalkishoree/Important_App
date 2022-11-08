<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class OrderPanelDetail extends Model
{
    protected $fillable = ['db_host', 'db_port', 'db_name', 'db_username', 'db_password', 'is_active'];

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
