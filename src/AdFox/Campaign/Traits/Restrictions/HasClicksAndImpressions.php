<?php

namespace AdFox\Campaign\Traits\Restrictions;

use AdFox\Campaign\Banner\Banner;

trait HasClicksAndImpressions {

	/**
	 * Total clicks count
	 *
	 * @var int
	 */
	public $clicks = 0;

	/**
	 * Clicks count for today
	 *
	 * @var int
	 */
	public $clicksToday = 0;

	/**
	 * Clicks count for last hour
	 *
	 * @var int
	 */
	public $clicksHour = 0;

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
	 * Impressions clicks count
	 *
	 * @var int
	 */
	public $impressions = 0;

	/**
	 * Impressions count for today
	 *
	 * @var int
	 */
	public $impressionsToday = 0;

	/**
	 * Impressions count for last hour
	 *
	 * @var int
	 */
	public $impressionsHour = 0;

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
		$this->maxClicks = is_null($total) ? $this->getMaxClicks() : (int) $total;
		$this->maxClicksPerDay = is_null($day) ? $this->getMaxClicksPerDay() : (int) $day;
		$this->maxClicksPerHour = is_null($hour) ? $this->getMaxClicksPerHour() : (int) $hour;

		return $this;
	}

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
	 * Returns this trait attributes
	 *
	 * @return array
	 */
	public static function getHasClicksAndImpressionsAttributes()
	{
		return [
			'maxClicks', 'maxClicksPerDay', 'maxClicksPerHour',
			'maxImpressions', 'maxImpressionsPerDay', 'maxImpressionsPerHour',
			'clicks', 'clicksToday', 'clicksHour',
			'impressions', 'impressionsToday', 'impressionsHour'
		];
	}

	/**
	 * Sets this trait attributes
	 *
	 * @param static $instatce
	 * @param array $attributes
	 */
	public static function setHasClicksAndImpressionsAttributes($instatce, $attributes)
	{
		$instatce->setClicksLimits($attributes['maxClicks'], $attributes['maxClicksPerDay'], $attributes['maxClicksPerHour']);
		$instatce->setImpressionsLimits($attributes['maxImpressions'], $attributes['maxImpressionsPerDay'], $attributes['maxImpressionsPerHour']);
	}
}