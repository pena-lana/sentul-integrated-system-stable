<?php

namespace App\Models\Transaction\Rollie;

use App\Models\ResourceModel;
use Illuminate\Database\Eloquent\Model;

class PaletPpq extends ResourceModel
{
    protected $connection   = 'transaction_data';
    protected $table        = 'palet_ppqs';
    protected $guarded      = ['id'];
    public static function boot()
    {
        parent::boot();
    }
    public function palet()
    {
        return $this->belongsTo('App\Models\Transaction\Rollie\Palet', 'palet_id', 'id');
    }
    public function ppq()
    {
        return $this->belongsTo('App\Models\Transaction\Rollie\Ppq', 'ppq_id', 'id');
    }
}
