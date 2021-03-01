<?php

namespace App\Models\Transaction\Rollie;

use Illuminate\Database\Eloquent\Model;
use App\Models\ResourceModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class RPDFillingHead extends ResourceModel
{
    protected $connection   = 'transaction_data';
    protected $table = 'rpd_filling_heads';
    protected $guarded  = ['id'];
    public static function boot()
    {
        parent::boot();
    }

    public function product()
    {
        return $this->belongsTo('App\Models\Master\Product', 'product_id', 'id');
    }
    public function woNumbers()
    {
        return $this->hasMany('App\Models\Transaction\Rollie\WoNumber', 'rpd_filling_head_id', 'id');
    }
    public function rpdFillingDetailPis()
    {
        return $this->hasMany('App\Models\Transaction\Rollie\RPDFillingDetailPi', 'rpd_filling_head_id', 'id');
    }

    public function rpdFillingDetailPiAtEvents()
    {
        return $this->hasMany('App\Models\Transaction\Rollie\RPDFillingDetailPiAtEvent', 'rpd_filling_head_id', 'id');
    }
}
