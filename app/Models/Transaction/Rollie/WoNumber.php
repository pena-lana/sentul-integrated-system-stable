<?php

namespace App\Models\Transaction\Rollie;

use Illuminate\Database\Eloquent\Model;
use App\Models\ResourceModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class WoNumber extends ResourceModel
{
    protected $connection   = 'transaction_data';
    protected $table        = 'wo_numbers';
    protected $guarded      = ['id'];
    public static function boot()
    {
        parent::boot();
    }
    public function product()
    {
        return $this->belongsTo('App\Models\Master\Product', 'product_id', 'id');
    }
    public function plan()
    {
        return $this->belongsTo('App\Models\Master\Plan', 'plan_id', 'id');
    }

    /* public function cppHead()
    {
        return $this->belongsTo('App\Models\Transaction\Rollie\CppHead', 'cpp_head_id', 'id');
    } */

    /* public function rpdFillingHead()
    {
        return $this->belongsTo('App\Models\Transaction\Rollie\RpdFillingHead', 'rpd_filling_head_id', 'id');
    } */
    /* public function cppDetails()
    {
        return $this->hasMany('App\Models\Transaction\Rollie\CppDetail', 'wo_number_id', 'id');
    } */
    /* public function rpdFillingDetailPis()
    {
        return $this->hasMany('App\Models\Transaction\Rollie\RpdFillingDetailPi', 'wo_number_id', 'id');
    } */

   /*  public function rpdFillingDetailAtEvents()
    {
        return $this->hasMany('App\Models\Transaction\Rollie\RpdFillingDetailAtEvent', 'wo_number_id', 'id');
    } */
}
