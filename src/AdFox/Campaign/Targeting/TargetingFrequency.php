<?php

namespace AdFox\Campaign\Targeting;

use AdFox\AdFox;
use AdFox\Campaign\Targeting\Contracts\Targeting;

class TargetingFrequency implements Targeting{

	const FREQUENCY_NONE = 0;
	const FREQUENCY_HOUR = 1;
	const FREQUENCY_4_HOURS = 2;
	const FREQUENCY_6_HOURS = 3;
	const FREQUENCY_12_HOURS = 4;
	const FREQUENCY_DAY = 5;
	const FREQUENCY_WEEK = 6;
	const FREQUENCY_2_WEEKS = 9;
	const FREQUENCY_MONTH = 7;
	const FREQUENCY_OTHER = 8;

	/**
	 * Impressions limit to unique user
	 *
	 * @var int
	 */
	protected $maxUniqueImpressions = 0;

	/**
	 * Clicks limit to unique user
	 *
	 * @var int
	 */
	protected $maxUniqueClicks = 0;

	/**
	 * Period start date
	 *
	 * @var string
	 */
	protected $periodStartDate;

	/**
	 * Clicks frequency period
	 *
	 * @var int
	 */
	protected $clicksFrequency;

	/**
	 * Clicks limit for pertiod
	 *
	 * @var int
	 */
	protected $clicksLimit = 1;

	/**
	 * Clicks special period
	 *
	 * @var string
	 */
	protected $clicksSpecialPeriod;

	/**
	 * Clicks min period
	 *
	 * @var string
	 */
	protected $clicksMinPeriod;

	/**
	 * Impressions frequency period
	 *
	 * @var int
	 */
	protected $impressionsFrequency;

	/**
	 * Impressions limit for pertiod
	 *
	 * @var int
	 */
	protected $impressionsLimit = 1;

	/**
	 * Impressions special period
	 *
	 * @var string
	 */
	protected $impressionsSpecialPeriod;

	/**
	 * Impressions min period
	 *
	 * @var string
	 */
	protected $impressionsMinPeriod;

	/**
	 * Set impressions limit to unique user
	 *
	 * @param int $limit
	 */
	public function setmaxUniqueImpressions($limit)
	{
		$this->maxUniqueImpressions = $limit;
	}

	/**
	 * Set clicks limit to unique user
	 *
	 * @param int $limit
	 */
	public function setmaxUniqueClicks($limit)
	{
		$this->maxUniqueClicks = $limit;
	}

	/**
	 * Set period start date
	 *
	 * @param string|int $date
	 * @return $this
	 */
	public function setStartDate($date)
	{
		$this->periodStartDate = AdFox::convertDate($date);

		return $this;
	}

	/**
	 * Sets clicks frequency
	 *
	 * @param $frequency
	 * @param $limit
	 * @param string $specialPeriod
	 * @param string $minPeriod
	 * @param null $periodStartDate
	 *
	 * @return TargetingFrequency
	 */
	public function setClicksFrequency($frequency, $limit, $specialPeriod = '0:01', $minPeriod = '0:01', $periodStartDate = null)
	{
		return $this->setFrequency('clicks', $frequency, $limit, $specialPeriod, $minPeriod, $periodStartDate);
	}

	/**
	 * Sets impressions frequency
	 *
	 * @param $frequency
	 * @param $limit
	 * @param string $specialPeriod
	 * @param string $minPeriod
	 * @param null $periodStartDate
	 *
	 * @return TargetingFrequency
	 */
	public function setImpressionsFrequency($frequency, $limit, $specialPeriod = '0:01', $minPeriod = '0:01', $periodStartDate = null)
	{
		return $this->setFrequency('impressions', $frequency, $limit, $specialPeriod, $minPeriod, $periodStartDate);
	}

	/**
	 * Sets frequency
	 *
	 * @param $type
	 * @param $frequency
	 * @param $limit
	 * @param $specialPeriod
	 * @param $minPeriod
	 * @param $periodStartDate
	 * @return $this
	 */
	protected function setFrequency($type, $frequency, $limit, $specialPeriod, $minPeriod, $periodStartDate)
	{
		if (in_array($type, AdFox::getConstants('FREQUENCY', static::class)))
		{
			$this->{$type . 'Frequency'} = $frequency;
			$this->{$type . 'Limit'} = $limit;
			$this->{$type . 'SpecialPeriod'} = $specialPeriod;

			if ($frequency == static::FREQUENCY_OTHER)
			{
				$this->periodStartDate = AdFox::convertDate($periodStartDate);
				$this->{$type . 'MinPeriod'} = $minPeriod;
			}
		}

		return $this;
	}

	/**
	 * Determines if other frequency period is setted
	 *
	 * @return bool
	 */
	protected function isOtherFrequency()
	{
		return $this->impressionsFrequency == static::FREQUENCY_OTHER or $this->clicksFrequency == static::FREQUENCY_OTHER;
	}

	/**
	 * Get Object type. String constant from Targeting class.
	 *
	 * @return string
	 */
	public function getType()
	{
		return Targeting::TYPE_FREQUENCY;
	}

	/**
	 * Get params of this targeting
	 *
	 * @return array
	 */
	public function getParams()
	{
		$params = [
			'maxUniqueImpressions' => $this->maxUniqueImpressions,
			'maxUniqueClicks' => $this->maxUniqueClicks,

			'frequencyTypeImpressions' => $this->impressionsFrequency,
			'impressionsPerPeriod' => $this->impressionsLimit,
			'minimalPeriodImpressions' => $this->impressionsMinPeriod,

			'frequencyTypeClicks' => $this->clicksFrequency,
			'clicksPerPeriod' => $this->clicksLimit,
			'minimalPeriodClicks' => $this->clicksMinPeriod,
		];

		if ($this->isOtherFrequency())
		{
			$params['datePeriod'] = $this->periodStartDate;
			$params['uniquePeriodClicks'] = $this->clicksSpecialPeriod;
			$params['uniquePeriodClicks'] = $this->clicksSpecialPeriod;
		}

		return $params;
	}
}