<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{

    protected $fillable = ['user_is', 'title', 'description', 'completed'];

	protected $attributes = [
		'completed' => false,
	];
}
