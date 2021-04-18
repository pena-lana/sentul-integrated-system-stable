<?php

namespace App\Models\Transaction\Rollie;

use Illuminate\Database\Eloquent\Model;
use App\Models\ResourceModel;

class CppHead extends ResourceModel
{
    protected $connection   = 'transaction_data';
    protected $table        = 'cpp_heads';
    protected $guarded      = ['id'];
    public static function boot()
    {
        parent::boot();
    }
    public function cppDetails()
    {
        return $this->hasMany('App\Models\Transaction\Rollie\CppDetail', 'cpp_head_id', 'id');
    }
    public function woNumbers()
    {
        return $this->hasMany('App\Models\Transaction\Rollie\WoNumber', 'cpp_head_id', 'id');
    }
    public function product()
    {
        return $this->belongsTo('App\Models\Master\Product', 'product_id', 'id');
    }

/*     public function analisaKimia()
    {
        return $this->belongsTo('App\Models\Transaction\Rollie\AnalisaKimia', 'analisa_kimia_id', 'id');
    }
    public function analisaMikro()
    {
        return $this->belongsTo('App\Models\Transaction\Rollie\AnalisaMikro', 'analisa_mikro_id', 'id');
    } */
    public function ppq()
    {
        return $this->hasOne('App\Models\Transaction\Rollie\Ppq', 'cpp_head_id','id');
    }
}
