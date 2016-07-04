<?php

namespace AdFox\Campaigns\Traits\Restrictions;

trait HasClicksRestrictions {
	
	/**
	 * Total clicks limit
	 *
	 * @var int
	 */
	protected $maxClicks = 0;

	/**
	 * Clicks limit per day
	 *
	 * @var int
	 */
	protected $maxClicksPerDay = 0;

	/**
	 * Clicks limit per hour
	 *
	 * @var int
	 */
	protected $maxClicksPerHour = 0;
	
	/**
	 * Set clicks limits for the object.
	 * 0 for no limit
	 *
	 * @param int $total
	 * @param int $day
	 * @param int $hour
	 * @return $this
	 */
	public function setClicksLimits($total = null, $day = null, $hour = null)
	{
		$this->maxClicks = is_null($total) ? $this->getMaxClicks() : $total;
		$this->maxClicksPerDay = is_null($day) ? $this->getMaxClicksPerDay() : $day;
		$this->maxClicksPerHour = is_null($hour) ? $this->getMaxClicksPerHour() : $hour;

		return $this;
	}

	/**
	 * Get all type clicks limits of the object
	 *
	 * @return array
	 */
	public function getClicksLimits()
	{
		return [
			'total' => $this->getMaxClicks(),
			'day' => $this->getMaxClicksPerDay(),
			'hour' => $this->getMaxClicksPerHour(),
		];
	}

	/**
	 * Get clicks limits of the object
	 *
	 * @return int
	 */
	public function getMaxClicks()
	{
		return $this->maxClicks;
	}

	/**
	 * Get clicks per day limits of the object
	 *
	 * @return int
	 */
	public function getMaxClicksPerDay()
	{
		return $this->maxClicksPerDay;
	}

	/**
	 * Get clicks per hour limits of the object
	 *
	 * @return int
	 */
	public function getMaxClicksPerHour()
	{
		return $this->maxClicksPerHour;
	}
}