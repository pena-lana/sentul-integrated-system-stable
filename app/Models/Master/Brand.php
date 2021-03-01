<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use App\Models\ResourceModel;
class Brand extends ResourceModel
{
    protected $connection 	= 'master_data';
	protected $guarded 		= ['id'];
	protected $table 		= 'brands';

    public static function boot()
    {
        parent::boot();
    }
    public function subbrands()
    {
        return $this->hasMany('App\Models\Master\Subbrand', 'brand_id', 'id');
    }
    public function company()
    {
        return $this->belongsTo('App\Models\Master\Company', 'company_id', 'id');
    }
}
