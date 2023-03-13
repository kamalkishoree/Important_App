<?php

namespace App\Model\Order;

use Illuminate\Database\Eloquent\Model;

class CategoryTranslation extends Model
{

    protected $connection = 'royoorder';

    protected $table = 'category_translations';

}
