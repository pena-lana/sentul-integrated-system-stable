<?php

namespace App\Models\Master;


use Illuminate\Database\Eloquent\Model;
use App\Models\ResourceModel;

class KategoriPpq extends ResourceModel
{
    protected $connection   = 'master_data';
    protected $table        = 'kategori_ppqs';
    protected $guarded      = ['id'];

    public static function boot()
    {
        parent::boot();
    }

    public function jenisPpq()
    {
        return $this->belongsTo('App\Models\Master\JenisPpq', 'jenis_ppq_id', 'id');
    }

    public function ppqs()
    {
        return $this->hasMany('App\Models\Transaction\Rollie\PPQ', 'kategori_ppq_id', 'id');
    }
}
