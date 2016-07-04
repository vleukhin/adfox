<?php

namespace AdFox\Campaigns\Traits;

trait HasRestrictions {

	/**
	 * Total impressions limit
	 *
	 * @var int
	 */
	protected $maxImpressions = 0;

	/**
	 * Impressions limit per day
	 *
	 * @var int
	 */
	protected $maxImpressionsPerDay = 0;

	/**
	 * Impressions limit per hour
	 *
	 * @var int
	 */
	protected $maxImpressionsPerHour = 0;

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
	 * Total clicks limit
	 *
	 * @var int
	 */
	protected $maxActiveEvents = 0;

	/**
	 * ActiveEvents limit per day
	 *
	 * @var int
	 */
	protected $maxActiveEventsPerDay = 0;

	/**
	 * ActiveEvents limit per hour
	 *
	 * @var int
	 */
	protected $maxActiveEventsPerHour = 0;

	/**
	 * Set impressions limits for the object.
	 * 0 for no limit
	 *
	 * @param int $total
	 * @param int $day
	 * @param int $hour
	 * @return $this
	 */
	public function setImpressionsLimits($total = null, $day = null, $hour = null)
	{
		$this->maxImpressions = is_null($total) ? $this->getMaxImpressions() : $total;
		$this->maxImpressionsPerDay = is_null($day) ? $this->getMaxImpressionsPerDay() : $day;
		$this->maxImpressionsPerHour = is_null($hour) ? $this->getMaxImpressionsPerHour() : $hour;
		
		return $this;
	}

	/**
	 * Get all type impressions limits of the object
	 *
	 * @return array
	 */
	public function getImpressionsLimits()
	{
		return [
			'total' => $this->getMaxImpressions(),
			'day' => $this->getMaxImpressionsPerDay(),
			'hour' => $this->getMaxImpressionsPerHour(),
		];
	}

	/**
	 * Get impressions limits of the object
	 *
	 * @return int
	 */
	public function getMaxImpressions()
	{
		return $this->maxImpressions;
	}

	/**
	 * Get impressions per day limits of the object
	 *
	 * @return int
	 */
	public function getMaxImpressionsPerDay()
	{
		return $this->maxImpressionsPerDay;
	}

	/**
	 * Get impressions per hour limits of the object
	 *
	 * @return int
	 */
	public function getMaxImpressionsPerHour()
	{
		return $this->maxImpressionsPerHour;
	}

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

	/**
	 * Set events limits for the object.
	 * 0 for no limit
	 *
	 * @param int $total
	 * @param int $day
	 * @param int $hour
	 * @return $this
	 */
	public function setActiveEventsLimits($total = null, $day = null, $hour = null)
	{
		$this->maxActiveEvents = is_null($total) ? $this->getMaxActiveEvents() : $total;
		$this->maxActiveEventsPerDay = is_null($day) ? $this->getMaxActiveEventsPerDay() : $day;
		$this->maxActiveEventsPerHour = is_null($hour) ? $this->getMaxActiveEventsPerHour() : $hour;

		return $this;
	}

	/**
	 * Get all type events limits of the object
	 *
	 * @return array
	 */
	public function getActiveEventsLimits()
	{
		return [
			'total' => $this->getMaxActiveEvents(),
			'day' => $this->getMaxActiveEventsPerDay(),
			'hour' => $this->getMaxActiveEventsPerHour(),
		];
	}

	/**
	 * Get events limits of the object
	 *
	 * @return int
	 */
	public function getMaxActiveEvents()
	{
		return $this->maxActiveEvents;
	}

	/**
	 * Get events per day limits of the object
	 *
	 * @return int
	 */
	public function getMaxActiveEventsPerDay()
	{
		return $this->maxActiveEventsPerDay;
	}

	/**
	 * Get events per hour limits of the object
	 *
	 * @return int
	 */
	public function getMaxActiveEventsPerHour()
	{
		return $this->maxActiveEventsPerHour;
	}

}