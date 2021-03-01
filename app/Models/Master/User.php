<?php

namespace App\Models\Master;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
   	use Notifiable;
    protected $connection   = 'master_data';
    protected $table        = 'users';
    protected $guarded      = ['id'];

    public function employee()
    {
        return $this->belongsTo('App\Models\Master\Employee');
    }
    public function applicationPermissions()
    {
        return $this->hasMany('App\Models\Master\ApplicationPermission');
    }
    public function menuPermissions()
    {
        return $this->hasMany('App\Models\Master\MenuPermission', 'user_id', 'id');
    }
}
