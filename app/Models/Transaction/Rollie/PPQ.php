<?php

namespace App\Models\Transaction\Rollie;

use Illuminate\Database\Eloquent\Model;
use App\Models\ResourceModel;

class PPQ extends ResourceModel
{
    protected $connection   = 'transaction_data';
    protected $table        = 'ppqs';
    protected $guarded      = ['id'];
    public static function boot()
    {
        parent::boot();
    }

    public function paletPpqs()
    {
    	return $this->hasMany('App\Models\Transaction\Rollie\PaletPpq', 'ppq_id', 'id');
    }

    public function rpdFillingDetailPi()
    {
        return $this->belongsTo('App\Models\Transaction\Rollie\RpdFillingDetailPi', 'rpd_filling_detail_pi_id', 'id');
    }

    public function kategoriPpq()
    {
        return $this->belongsTo('App\Models\Master\KategoriPpq', 'kategori_ppq_id', 'id');
    }

    public function cppHead()
    {
        return $this->belongsTo('App\Models\Transaction\Rollie\CppHead', 'cpp_head_id', 'id');
    }
    public function userCreate()
    {
        return $this->belongsTo('App\Models\Master\User', 'created_by', 'id');
    }
/*     public function followUpPpq()
    {
        return $this->hasOne('App\Models\Transaction\Rollie\FollowUpPpq', 'ppq_id', 'id');
    }
    public function rkj()
    {
        return $this->hasOne('App\Models\Transaction\Rollie\Rkj', 'ppq_id', 'id');
    }
    public function analisaMikroDetails()
    {
        return $this->hasMany('App\Models\Transaction\Rollie\AnalisaMikroDetail', 'ppq_id', 'id');
    }
    public function analisaMikroResampling()
    {
        return $this->hasOne('App\Models\Transaction\Rollie\AnalisaMikroResampling', 'ppq_id', 'id');
    } */
}
