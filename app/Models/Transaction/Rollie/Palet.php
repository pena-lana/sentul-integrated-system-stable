<?php

namespace App\Models\Transaction\Rollie;

use App\Models\ResourceModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Palet extends ResourceModel
{
    use softDeletes;
    protected $connection   = 'transaction_data';
    protected $table        = 'palets';
    protected $guarded      = ['id'];
    public static function boot()
    {
        parent::boot();
    }
    public function cppDetail()
    {
        return $this->belongsTo('App\Models\Transaction\Rollie\CppDetail', 'cpp_detail_id', 'id');
    }
    public function paletPpqs()
    {
        return $this->hasMany('App\Models\Transaction\Rollie\PaletPpq', 'palet_id', 'id');
    }
    public function atEvents()
    {
        return $this->hasMany('App\Models\Transaction\Rollie\RpdFillingDetailPiAtEvent', 'palet_id','id');
    }
}
