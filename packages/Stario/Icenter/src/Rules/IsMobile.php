<?php

namespace Stario\Icenter\Rules;

use Illuminate\Contracts\Validation\Rule;

class IsMobile implements Rule {

	/**
	 * Determine if the validation rule passes.
	 *
	 * @param  string  $attribute
	 * @param  mixed  $value
	 * @return bool
	 */
	public function passes($attribute, $value) {
		return preg_match('/^1[23456789]\d{9}/', $value);
	}

	/**
	 * Get the validation error message.
	 *
	 * @return string
	 */
	public function message() {
		return trans('validation.mobile');
	}
}
