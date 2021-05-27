<?php

// namespace App\Model;

// use Illuminate\Database\Eloquent\Model;

// class SubAdmin extends Model
// {
//     protected $table = 'sub_admin';
//     protected $fillable = ['name','email', 'phone_number', 'password', 'status'];
// }

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class SubAdmin extends Authenticatable
{
    use Notifiable;
    protected $table = 'sub_admin';
    protected $guard = 'subadmin';
    protected $fillable = ['name','email', 'phone_number', 'password', 'status','all_team_access'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
}
