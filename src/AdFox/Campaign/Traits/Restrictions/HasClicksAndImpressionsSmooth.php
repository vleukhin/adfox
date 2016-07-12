<?php

namespace AdFox\Campaign\Traits\Restrictions;

use AdFox\AdFox;

trait HasClicksAndImpressionsSmooth {

	/**
	 * Type ID of clicks smooth
	 *
	 * @var int
	 */
	protected $clicksSmoothTypeID;

	/**
	 * Type ID of impressions smooth
	 *
	 * @var int
	 */
	protected $impressionsSmoothTypeID;

	/**
	 * Sets clicks smooth type
	 *
	 * @param int $typeId SMOOTH_* constant from AdFox class
	 *
	 * @return $this
	 */
	public function setClicksSmoothType($typeId)
	{
		return $this->setSmooth('clicks', $typeId);
	}

	/**
	 * Sets impresssions smooth type
	 *
	 * @param int $typeId SMOOTH_* constant from AdFox class
	 *
	 * @return $this
	 */
	public function setImpresssionsSmoothType($typeId)
	{
		return $this->setSmooth('impressions', $typeId);
	}

	/**
	 * Sets smooth type
	 *
	 * @param string $type clicks or impressions
	 * @param int $typeId SMOOTH_* constant from AdFox class
	 * @return $this
	 */
	protected function setSmooth($type, $typeId)
	{
		if (in_array($typeId, AdFox::getConstants('SMOOTH')))
		{
			$this->{$type . 'SmoothTypeID'} = $typeId;
		}

		return $this;
	}

	/**
	 * Returns this trait attributes
	 *
	 * @return array
	 */
	public static function getHasClicksAndImpressionsSmoothAttributes()
	{
		return ['clicksSmoothTypeID', 'impressionsSmoothTypeID'];
	}

	/**
	 * Sets this trait attributes
	 *
	 * @param $instatce
	 * @param $attributes
	 */
	public static function setHasClicksAndImpressionsSmoothAttributes($instatce, $attributes)
	{
		$instatce->setImpresssionsSmoothType($attributes['impressionsSmoothTypeID']);
		$instatce->setClicksSmoothType($attributes['clicksSmoothTypeID']);
	}
}