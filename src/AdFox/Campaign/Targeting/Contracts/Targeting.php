<?php

namespace AdFox\Campaign\Targeting\Contracts;

interface Targeting
{
	const TYPE_TIME = 'targetingTime';

	/**
	 * Get Object type. String constant from Targeting class.
	 *
	 * @return string
	 */
	public function getType();

	/**
	 * Get params of this targeting
	 *
	 * @return mixed
	 */
	public function getParams();
}