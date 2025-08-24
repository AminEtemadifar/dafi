<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
	use HasFactory;

	protected $fillable = [
		'submit_id',
		'mobile',
		'name',
		'amount',
		'gateway',
		'authority',
		'ref_id',
		'card_pan',
		'status',
		'raw_request',
		'raw_response',
		'verified_at',
	];

	protected $casts = [
		'raw_request' => 'array',
		'raw_response' => 'array',
		'verified_at' => 'datetime',
	];
}