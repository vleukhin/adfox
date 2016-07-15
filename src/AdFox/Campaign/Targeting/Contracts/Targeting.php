<?php

namespace AdFox\Campaign\Targeting\Contracts;

interface Targeting
{
	const TYPE_TIME = 'targetingTime';
	const TYPE_USER = 'targetingUser';
	const TYPE_FREQUENCY = 'targetingFrequency';

	/**
	 * Get Object type. String constant from Targeting class.
	 *
	 * @return string
	 */
	public function getType();

	/**
	 * Get params of this targeting
	 *
	 * @return array
	 */
	public function getParams();
}