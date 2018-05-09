<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 23 Nov 2017 11:32:02 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Register
 * 
 * @property int $id
 * @property string $name
 * @property string $surname
 * @property int $age
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property int $policy_id
 * @property string $gender
 * 
 * @property \App\Models\Policy $policy
 *
 * @package App\Models
 */
class Register extends Eloquent
{
	protected $casts = [
		'age' => 'int',
		'policy_id' => 'int'
	];

	protected $fillable = [
		'name',
		'surname',
		'age',
		'policy_id',
		'gender'
	];

	public function policy()
	{
		return $this->belongsTo(\App\Models\Policy::class);
	}
}
