<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 23 Nov 2017 11:32:02 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Policy
 * 
 * @property int $id
 * @property string $policy
 * @property bool $active
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \Illuminate\Database\Eloquent\Collection $registers
 *
 * @package App\Models
 */
class Policy extends Eloquent
{
	protected $casts = [
		'active' => 'bool'
	];

	protected $fillable = [
		'policy',
		'active'
	];

	public function registers()
	{
		return $this->hasMany(\App\Models\Register::class);
	}
}
