<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class AgentSavedPaymentMethod extends Model
{
    use HasFactory;
    use SoftDeletes;
}
