<?php

namespace AdFox\Campaigns\Traits;

trait HasLevel {

	/**
	 * Object level. From 1 (highest) to 10.
	 *
	 * @var int
	 */
	protected $level = 1;

	/**
	 * Get Object level.
	 *
	 * @return int
	 */
	public function getLevel()
	{
		return $this->level;
	}

	/**
	 * Set Object level. From 1 (highest) to 10
	 *
	 * @param $level
	 * @return $this
	 */
	public function setLevel($level)
	{
		if ($level >= 1 and $level <= 10)
		{
			$this->level = $level;
		}

		return $this;
	}
}