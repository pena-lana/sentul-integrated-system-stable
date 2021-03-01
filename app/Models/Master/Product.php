<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use App\Models\ResourceModel;
class Product extends ResourceModel
{
	protected $connection 	= 'master_data';
	protected $guarded 		= ['id'];
	protected $table 		= 'products';
    public static function boot()
    {
        parent::boot();
    }
    public function fillingMachineGroupHead()
    {
        return $this->belongsTo('App\Models\Master\FillingMachineGroupHead', 'filling_machine_group_head_id', 'id');
    }

    public function productType()
    {
        return $this->belongsTo('App\Models\Master\ProductType', 'product_type_id', 'id');
    }

    public function subbrand()
    {
        return $this->belongsTo('App\Models\Master\Subbrand', 'subbrand_id', 'id');
    }
}
