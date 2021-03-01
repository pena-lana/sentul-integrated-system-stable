<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use App\Models\ResourceModel;
class FillingMachineGroupDetail extends ResourceModel
{
	protected $connection   = 'master_data';
    protected $table        = 'filling_machine_group_details';
    protected $guarded      = ['id'];

    public static function boot()
    {
        parent::boot();
    }
    public function fillingMachine()
    {
        return $this->belongsTo('App\Models\Master\FillingMachine', 'filling_machine_id', 'id');
    }
    public function fillingMachineGroupHead()
    {
        return $this->belongsTo('App\Models\Master\FillingMachineGroupHead', 'filling_machine_group_head_id', 'id');
    }
}
