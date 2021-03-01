<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use App\Models\ResourceModel;

class JenisPpq extends ResourceModel
{
    protected $connection   = 'master_data';
    protected $table        = 'jenis_ppqs';
    protected $guarded      = ['id'];

    public static function boot()
    {
        parent::boot();
    }

    public function kategoriPpqs()
    {
        return $this->hasMany('App\Models\Master\KategoriPpq', 'jenis_ppq_id', 'id');
    }
}
