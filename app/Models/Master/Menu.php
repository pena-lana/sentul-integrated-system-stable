<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use App\Models\ResourceModel;
class Menu extends ResourceModel
{
	protected $connection 	= 'master_data';
	protected $guarded 		= ['id'];
	protected $table 		= 'menus';
    public static function boot()
    {
        parent::boot();
    }
	public function menuPermissions()
    {
        return $this->hasMany('App\Models\Master\MenuPermission', 'menu_id', 'id');
    }
    public function application()
    {
        return $this->belongsTo('App\Models\Master\Application', 'application_id', 'id');
    }

    public function parentMenu()
    {
        return $this->belongsTo('App\Models\Master\Menu', 'parent_id', 'id');
    }
        public function childMenus()
    {
        return $this->hasMany('App\Models\Master\Menu', 'parent_id', 'id');
    }
}
