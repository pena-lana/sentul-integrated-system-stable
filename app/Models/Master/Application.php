<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use App\Models\ResourceModel;
class Application extends ResourceModel
{
    protected $connection 	= 'master_data';
	protected $guarded 		= ['id'];
	protected $table 		= 'applications';

    public static function boot()
    {
        parent::boot();
    }
    public function applicationPermissions()
    {
        return $this->hasMany('App\Models\Master\ApplicationPermission', 'application_id', 'id');
    }
    public function menus()
    {
        return $this->hasMany('App\Models\Master\Menu', 'application_id', 'id');
    }
}
