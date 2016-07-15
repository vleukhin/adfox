<?php

namespace AdFox\Campaign\Traits\Restrictions;

trait HasActiveEventsRestrictions {

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
		$this->maxActiveEvents = is_null($total) ? $this->getMaxActiveEvents() : (int) $total;
		$this->maxActiveEventsPerDay = is_null($day) ? $this->getMaxActiveEventsPerDay() : (int) $day;
		$this->maxActiveEventsPerHour = is_null($hour) ? $this->getMaxActiveEventsPerHour() : (int) $hour;

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

	/**
	 * Returns this trait attributes
	 * 
	 * @return array
	 */
	public static function getHasActiveEventsRestrictionsAttributes()
	{
		return ['maxActiveEvents', 'maxActiveEventsPerDay', 'maxActiveEventsPerHour'];
	}

	/**
	 * Sets this trait attributes
	 *
	 * @param static $instance
	 * @param array $attributes
	 */
	public static function setHasActiveEventsRestrictionsAttributes($instance, $attributes)
	{
		$instance->setActiveEventsLimits($attributes['maxActiveEvents'], $attributes['maxActiveEventsPerDay'], $attributes['maxActiveEventsPerHour']);
	}

}