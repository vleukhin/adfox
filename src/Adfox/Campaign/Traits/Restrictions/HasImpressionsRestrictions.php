<?php

namespace AdFox\Campaigns\Traits\Restrictions;

trait HasImpressionsRestrictions {

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
		$this->maxImpressions = is_null($total) ? $this->getMaxImpressions() : (int) $total;
		$this->maxImpressionsPerDay = is_null($day) ? $this->getMaxImpressionsPerDay() : (int) $day;
		$this->maxImpressionsPerHour = is_null($hour) ? $this->getMaxImpressionsPerHour() : (int) $hour;

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
}