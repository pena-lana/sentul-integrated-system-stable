<?php
namespace App\Models\Transaction\Rollie;

use Illuminate\Database\Eloquent\Model;
use App\Models\ResourceModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class RPDFillingDetailPiAtEvent extends ResourceModel
{
    protected $connection   = 'transaction_data';
    protected $table        = 'rpd_filling_detail_at_events';
    protected $guarded      = ['id'];
    use softDeletes;

    public static function boot()
    {
        parent::boot();
    }

    public function rpdFillingHead()
    {
        return $this->belongsTo('App\Models\Transaction\Rollie\RPDFillingHead', 'rpd_filling_head_id', 'id');
    }

    public function woNumber()
    {
        return $this->belongsTo('App\Models\Transaction\Rollie\WoNumber', 'wo_number_id', 'id');
    }

    public function fillingMachine()
    {
        return $this->belongsTo('App\Models\Master\FillingMachine', 'filling_machine_id', 'id');
    }

    public function fillingSampelCode()
    {
        return $this->belongsTo('App\Models\Master\FillingSampelCode', 'filling_sampel_code_id', 'id');
    }
}
