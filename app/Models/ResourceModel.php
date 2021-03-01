<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;
class ResourceModel extends Model
{
	public static function boot()
	{
		parent::boot();
		static::creating(function($model)
		{
			$user 				= Auth::user();
			$model->created_by	= $user->id;
		});

		static::updating(function($model)
		{
			$user 				= Auth::user();
			$model->updated_by	= $user->id;
		});

		self::deleting(function($model)
		{
			$user 				= Auth::user();
			$model->deleted_by	= $user->id;
		});
	}
}
