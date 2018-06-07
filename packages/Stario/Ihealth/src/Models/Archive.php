<?php

namespace Stario\Ihealth\Models;

class Archive extends Model {
	protected $guarded = ['id'];
	protected $casts = [
		'result' => 'array',
		'abnormal' => 'array',
	];

	public function patient() {
		return $this->belongsTo(Patient::class);
	}

	public function scopePeriod($query, $from, $to) {
		return $query->whereBetween('checkdate', [$from, $to]);
	}

	public function scopeAbnormal($query) {
		return $query->whereNotNull('abnormal');
	}
}