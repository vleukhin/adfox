<?php

namespace AdFox\Campaign\Traits\Restrictions;

use AdFox\AdFox;

trait HasDateRestrictions {

	/**
	 * Start date of this object
	 *
	 * @var string
	 */
	protected $dateStart;

	/**
	 * End date of this object
	 *
	 * @var string
	 */
	protected $dateEnd;

	/**
	 * Set both start and end dates
	 *
	 * @param mixed $start timestamp or date formated according to AdFox::DATE_FORMAT
	 * @param mixed $end timestamp or date formated according to AdFox::DATE_FORMAT
	 */
	public function setDateRestrictions($start, $end)
	{
		$this->setStartDate($start);
		$this->setEndDate($end);
	}

	/**
	 * Set start date of the object
	 *
	 * @param mixed $date timestamp or date formated according to AdFox::DATE_FORMAT
	 * @return $this
	 */
	public function setStartDate($date)
	{
		return $this->setDate('Start', $date);

	}

	/**
	 * Set end date of the object
	 *
	 * @param mixed $date timestamp or date formated according to AdFox::DATE_FORMAT
	 * @return $this
	 */
	public function setEndDate($date)
	{
		return $this->setDate('End', $date);
	}

	/**
	 * Set date of the object
	 *
	 * @param string $type Start or End
	 * @param mixed $date timestamp or date formated according to AdFox::DATE_FORMAT
	 * @return $this
	 */
	protected function setDate($type, $date)
	{
		$this->{'date' . $type} = AdFox::convertDate($date);

		return $this;
	}

	/**
	 * Removes end date of the object
	 *
	 * @return $this
	 */
	public function removeEndDate()
	{
		$this->dateEnd = null;
		
		return $this;
	}

	/**
	 * Returns this trait attributes
	 *
	 * @return array
	 */
	public static function getHasDateRestrictionsAttributes()
	{
		return ['dateStart', 'dateEnd'];
	}

	/**
	 * Sets this trait attributes
	 *
	 * @param static $instance
	 * @param array $attributes
	 */
	public static function setHasDateRestrictionsAttributes($instance, $attributes)
	{
		$instance->setDateRestrictions($attributes['dateStart'], $attributes['dateEnd']);
	}

}