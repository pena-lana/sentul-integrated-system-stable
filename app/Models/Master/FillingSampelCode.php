<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use App\Models\ResourceModel;
class FillingSampelCode extends ResourceModel
{
    protected $connection   ='master_data';
    protected $table        ='filling_sampel_codes';
    protected $guarded      = ['id'];

    public static function boot()
    {
        parent::boot();
    }
    public function rpdFillingPiSampels()
    {
        return $this->hasMany('App\Models\Trasaction\Rollie\RpdFillingDetailPi', 'filling_sampel_code_id', 'id');
    }

    public function rpdFillingPiAtEventSampels()
    {
        return $this->hasMany('App\Models\Trasaction\Rollie\RpdFillingDetailPiAtEvent', 'filling_sampel_code_id', 'id');
    }

    public function fillingMachine()
    {
        return $this->belongsTo('App\Models\Master\FillingMachine', 'filling_machine_id', 'id');
    }

    public function productType()
    {
        return $this->belongsTo('App\Models\Master\ProductType', 'product_type_id', 'id');
    }
}
