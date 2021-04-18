<?php

namespace App\Models\Transaction\Rollie;
use App\Models\ResourceModel;
use Illuminate\Database\Eloquent\Model;

class CppDetail extends ResourceModel
{
    protected $connection   = 'transaction_data';
    protected $table        = 'cpp_details';
    protected $guarded      = ['id'];
    public static function boot()
    {
        parent::boot();
    }
    public function cppHead()
    {
        return $this->belongsTo('App\Models\Transaction\Rollie\CppHead', 'cpp_head_id', 'id');
    }

    public function woNumber()
    {
        return $this->belongsTo('App\Models\Transaction\Rollie\WoNumber', 'wo_number_id', 'id');
    }

    public function fillingMachine()
    {
        return $this->belongsTo('App\Models\Master\FillingMachine', 'filling_machine_id', 'id');
    }
    public function palets()
    {
        return $this->hasMany('App\Models\Transaction\Rollie\Palet', 'cpp_detail_id', 'id');
    }
}
