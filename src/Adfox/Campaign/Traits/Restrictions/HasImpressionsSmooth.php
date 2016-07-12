<?php

namespace AdFox\Campaign\Traits\Restrictions;

use AdFox\AdFox;

trait HasImpressionsSmooth {

	/**
	 * Type ID of impressions smooth
	 *
	 * @var int
	 */
	protected $impressionsSmoothTypeID;

	/**
	 * Sets impresssions smooth type
	 *
	 * @param $type
	 * @return $this
	 */
	public function setImpresssionsSmoothType($type)
	{
		if (in_array($type, AdFox::get_constants('IMP_SMOOTH')))
		{
			$this->impressionsSmoothTypeID = $type;
		}

		return $this;
	}

	/**
	 * Returns this trait attributes
	 *
	 * @return array
	 */
	public static function getHasImpressionsSmoothAttributes()
	{
		return ['impressionsSmoothTypeID'];
	}

	/**
	 * Sets this trait attributes
	 *
	 * @param $instatce
	 * @param $attributes
	 */
	public static function setHasImpressionsSmoothAttributes($instatce, $attributes)
	{
		$instatce->setImpresssionsSmoothType($attributes['impressionsSmoothTypeID']);
	}
}