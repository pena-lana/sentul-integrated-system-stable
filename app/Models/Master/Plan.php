<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use App\Models\ResourceModel;

class Plan extends ResourceModel
{
    protected $conntection 	='master_data';
	protected $table 		='plans';
	protected $guarded 		=['id'];
}
