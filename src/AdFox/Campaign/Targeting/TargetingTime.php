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
		$days = $this->getWeekDays();

		if (in_array($day, $days))
		{
			$this->impressions[$day] = $count;
		}
		elseif(is_null($day))
		{
			foreach ($days as $day)
			{
				$this->impressions[$day] = $count;
			}
		}

		return $this;
	}

	/**
	 * Get params of this targeting
	 *
	 * @return mixed
	 */
	public function getParams()
	{
		$params = [];
		$days = $this->getWeekDays();

		$params['isClientTime'] = $this->isClientTime;

		foreach ($days as $day)
		{
			if (isset($this->impressions[$day]))
			{
				$params['impressionsPer' . $day] = $this->impressions[$day];
			}

			if (isset($this->impressionsPerHour[$day]))
			{
				$params['impressionsPerHour' . $day] = $this->impressionsPerHour[$day];
			}

			for ($hour = 1; $hour <= self::MONTH_DAYS; $hour++)
			{
				if (isset($this->hours[$day][$hour]))
				{
					$params['day' . $day . 'hour' . $hour] = $this->hours[$day][$hour];
				}
			}
		}

		return $params;
	}
}