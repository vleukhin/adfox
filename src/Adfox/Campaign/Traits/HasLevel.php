<?php

namespace AdFox\Campaign\Traits;

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

	/**
	 * Returns this trait attributes
	 *
	 * @return array
	 */
	public static function getHasLevelAttributes()
	{
		return ['level'];
	}

	/**
	 * Sets this trait attributes
	 *
	 * @param static $instatce
	 * @param array $attributes
	 */
	public static function setHasLevelAttributes($instatce, $attributes)
	{
		$instatce->setLevel($attributes['level']);
	}
}