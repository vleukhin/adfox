<?php

namespace AdFox\Campaign\Traits;

use AdFox\AdFox;

trait HasStatus {

	/**
	 * Object status
	 *
	 * @var int
	 */
	protected $status;

	/**
	 * Check if object is active.
	 *
	 * @return bool
	 */
	public function isAcitve()
	{
		return $this->status == AdFox::OBJECT_STATUS_ACTIVE;
	}

	/**
	 * Check if object is paused.
	 *
	 * @return bool
	 */
	public function isPaused()
	{
		return $this->status == AdFox::OBJECT_STATUS_PAUSED;
	}

	/**
	 * Check if object is completed.
	 *
	 * @return bool
	 */
	public function isCompleted()
	{
		return $this->status == AdFox::OBJECT_STATUS_COMPLETED;
	}

	/**
	 * Set status of the object/
	 *
	 * @param $status
	 * @return $this
	 */
	public function setStatus($status)
	{
		if (in_array($status, AdFox::getConstants('OBJECT_STATUS')))
		{
			$this->status = $status;
		}

		return $this;
	}

	/**
	 * Returns this trait attributes
	 *
	 * @return array
	 */
	public static function getHasStatusAttributes()
	{
		return ['status'];
	}

	/**
	 * Sets this trait attributes
	 *
	 * @param static $instatce
	 * @param array $attributes
	 */
	public static function setHasStatusAttributes($instatce, $attributes)
	{
		$instatce->setStatus($attributes['status']);
	}
}