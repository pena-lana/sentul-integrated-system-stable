<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use App\Models\ResourceModel;
class FillingMachineGroupHead extends ResourceModel
{
	protected $connection   = 'master_data';
    protected $table        = 'filling_machine_group_heads';
    protected $guarded      = ['id'];

    public static function boot()
    {
        parent::boot();
    }

    public function fillingMachineGroupDetails()
    {
        return $this->hasMany('App\Models\Master\FillingMachineGroupDetail', 'filling_machine_group_head_id', 'id');
    }
}
