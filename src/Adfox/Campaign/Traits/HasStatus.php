<?php

namespace AdFox\Campaigns\Traits;

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
		if (in_array($status, [AdFox::OBJECT_STATUS_ACTIVE, AdFox::OBJECT_STATUS_PAUSED, AdFox::OBJECT_STATUS_COMPLETED]))
		{
			$this->status = $status;
		}

		return $this;
	}

}