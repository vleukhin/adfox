<?php
/**
 * Created by PhpStorm.
 * User: vleukhin
 * Date: 04.07.2016
 * Time: 13:30
 */

namespace AdFox\Campaigns;

use AdFox\AdFox;
use AdFox\Campaigns\Traits\HasRestrictions;
use AdFox\Campaigns\Traits\HasStatus;
use AdFox\Campaigns\Traits\HasLevel;

class Flight extends BaseObject{
	
	use HasStatus;
	use HasLevel;
	use HasRestrictions;

	const ROTATION_PRIORY = 0;
	const ROTATION_PRECENT = 1;

	/**
	 * Campaign ID
	 *
	 * @var int
	 */
	public $id = null;

	/**
	 * Adfox lib instance
	 *
	 * @var AdFox
	 */
	protected $adfox = null;

	/**
	 * Attributes that can be modified
	 *
	 * @var array
	 */
	protected $attributes = [
		'id', 'status', 'level',
		'maxImpressions', 'maxImpressionsPerDay', 'maxImpressionsPerHour',
		'maxClicks', 'maxClicksPerDay', 'maxClicksPerHour',
		'maxActiveEvents', 'maxActiveEventsPerDay', 'maxActiveEventsPerHour',
	];

	/**
	 * Campaign constructor.
	 *
	 * @param AdFox $adfox
	 * @param array $attributes
	 */
	public function __construct(AdFox $adfox, $attributes)
	{
		$this->id = $attributes['ID'];
		$this->status = $attributes['status'];
		$this->level = $attributes['level'];
		$this->setImpressionsLimits($attributes['maxImpressions'], $attributes['maxImpressionsPerDay'], $attributes['maxImpressionsPerHour']);
		$this->setClicksLimits($attributes['maxClicks'], $attributes['maxClicksPerDay'], $attributes['maxClicksPerHour']);
		$this->setActiveEventsLimits($attributes['maxActiveEvents'], $attributes['maxActiveEventsPerDay'], $attributes['maxActiveEventsPerHour']);

		parent::__construct($adfox);
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getType()
	{
		return AdFox::OBJECT_FLIGHT;
	}
}