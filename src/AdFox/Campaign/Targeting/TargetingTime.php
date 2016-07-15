<?php
/**
 * Created by Viktor Leukhin.
 * Tel: +7-926-797-5419
 * E-mail: vleukhin@ya.ru
 */

namespace AdFox\Campaign\Targeting;

use AdFox\Campaign\Targeting\Contracts\Targeting;

class TargetingTime implements Targeting{

	const DAY_MONDAY = 1;
	const DAY_TUESDAY = 2;
	const DAY_WEDNESDAY = 3;
	const DAY_THURSDAY = 4;
	const DAY_FRIDAY = 5;
	const DAY_SATURDAY = 6;
	const DAY_SUNDAY = 7;

	const MONTH_DAYS = 31;
	const DAY_HOURS = 24;

	/**
	 * Impressions per day limits
	 *
	 * @var array
	 */
	protected $impressions = [];

	/**
	 * Impressions per hour limits
	 *
	 * @var array
	 */
	protected $impressionsPerHour = [];

	/**
	 * Use client time. If false use default (Moscow GMT+3)
	 *
	 * @var bool
	 */
	protected $isClientTime = false;

	/**
	 * Two dimensions bool array of hours and days of week
	 *
	 * @var array[bool][bool]
	 */
	protected $hours = [];

	/**
	 * {@inheritdoc}
	 */
	public function getType()
	{
		return Targeting::TYPE_TIME;
	}

	/**
	 * Get all days of weeks constants
	 *
	 * @return array
	 */
	protected function getWeekDays()
	{
		return [
			self::DAY_MONDAY,
			self::DAY_TUESDAY,
			self::DAY_WEDNESDAY,
			self::DAY_THURSDAY,
			self::DAY_FRIDAY,
			self::DAY_SATURDAY,
			self::DAY_SUNDAY,
		];
	}

	/**
	 * Set impressions limit for a day or for all days of week
	 *
	 * @param int $count
	 * @param int $day
	 *
	 * @return $this
	 */
	public function setImpressions($count, $day = null)
	{
		return $this->_setImpressions('impressions', $count, $day);
	}

	/**
	 * Set impressions per hour limit for a day or for all days of week
	 *
	 * @param int $count
	 * @param int $day
	 *
	 * @return $this
	 */
	public function setImpressionsPerHour($count, $day = null)
	{
		return $this->_setImpressions('impressionsPerHour', $count, $day);
	}

	/**
	 * Set impressions limit for a day or for all days of week
	 *
	 * @param $type
	 * @param $count
	 * @param $day
	 *
	 * @return $this
	 */
	protected function _setImpressions($type, $count, $day)
	{
		$days = $this->getWeekDays();

		if (in_array($day, $days))
		{
			$this->{$type}[$day] = $count;
		}
		elseif(is_null($day))
		{
			foreach ($days as $day)
			{
				$this->{$type}[$day] = $count;
			}
		}

		return $this;
	}

	/**
	 * Set use client time or not
	 *
	 * @param bool $isClientTime
	 *
	 * @return $this
	 */
	public function setIsClientTime($isClientTime)
	{
		$this->isClientTime = $isClientTime;

		return $this;
	}

	/**
	 * Enable or disable impressions in given hours at given days
	 *
	 * @param array $hours
	 * @param array $days
	 * @return TargetingTime
	 */
	public function enableHours($hours = [], $days = [])
	{
		return $this->setHours(true, $hours, $days);
	}

	/**
	 * Enable or disable impressions in given hours at given days
	 *
	 * @param array $hours
	 * @param array $days
	 * @return TargetingTime
	 */
	public function disableHours($hours = [], $days = [])
	{
		return $this->setHours(false, $hours, $days);
	}

	/**
	 * Enable or disable impressions in given hours at given days
	 *
	 * @param $enabled
	 * @param $hours
	 * @param $days
	 * @return $this
	 */
	protected function setHours($enabled, $hours, $days)
	{
		if (!is_array($hours) or empty($hours))
		{
			$hours = range(1, self::DAY_HOURS);
		}

		if (!is_array($days) or empty($days))
		{
			$days = $this->getWeekDays();
		}

		foreach ($days as $day)
		{
			foreach ($hours as $hour)
			{
				$this->hours[$day][$hour] = $enabled;
			}
		}

		return $this;
	}

	/**
	 * Get params of this targeting
	 *
	 * @return array
	 */
	public function getParams()
	{
		$params = [];
		$days = $this->getWeekDays();

		$params['isClientTime'] = $this->isClientTime;

		foreach ($days as $day)
		{
			$params['impressionsPer' . $day] = isset($this->impressions[$day]) ? $this->impressions[$day] : 0;
			$params['impressionsPerHour' . $day] = isset($this->impressionsPerHour[$day]) ? $this->impressionsPerHour[$day] : 0;

			for ($hour = 1; $hour <= self::MONTH_DAYS; $hour++)
			{
				if (isset($this->hours[$day][$hour]))
				{
					$params['hour' . $hour . 'day' . $day] = (int) $this->hours[$day][$hour];
				}
			}
		}

		return $params;
	}
}