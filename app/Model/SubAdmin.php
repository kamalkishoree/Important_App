<?php
 
namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class SubAdmin extends Authenticatable
{
    use Notifiable;
    protected $guard = 'subadmin';
    protected $fillable = ['name','email', 'phone_number', 'password', 'status','all_team_access'];

  /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];
   
    
}
