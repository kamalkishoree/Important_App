<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TaskTeamTag extends Model
{
    protected $fillable = [
        'task_id'.'tag_id'
    ];
}
