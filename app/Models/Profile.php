<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Profile extends Model
{
	use HasFactory;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var list<string>
	 */
	protected $fillable = [
		'name',
		'dob',
		'gender',
		'interested_in',
		'programming_langs',
		'occupation',
		'showcase_code',
		'social_media',
		'user_id'
	];

	/**
	 * The attributes that should be hidden for serialization.
	 *
	 * @var list<string>
	 */
	protected $hidden = [];

	/**
	 * Get the attributes that should be cast.
	 *
	 * @return array<string, string>
	 */
	protected function casts(): array
	{
		return [
			'dob' => 'date',
			'programming_langs' => 'json',
		];
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public $timestamps = false;
}
