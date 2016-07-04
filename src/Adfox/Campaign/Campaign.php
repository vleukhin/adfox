<?php

namespace AdFox\Campaigns;

use AdFox\AdFox;
use AdFox\Campaigns\Traits\Restrictions\HasActiveEventsRestrictions;
use AdFox\Campaigns\Traits\Restrictions\HasClicksRestrictions;
use AdFox\Campaigns\Traits\Restrictions\HasImpressionsRestrictions;
use AdFox\Campaigns\Traits\HasStatus;

class Campaign extends BaseObject{

	use HasStatus;
	use HasActiveEventsRestrictions;
	use HasClicksRestrictions;
	use HasImpressionsRestrictions;

	/**
	 * Attributes that can be modified
	 *
	 * @var array
	 */
	protected $attributes = [
		'id', 'status',
		'maxImpressions', 'maxImpressionsPerDay', 'maxImpressionsPerHour',
		'maxClicks', 'maxClicksPerDay', 'maxClicksPerHour',
		'maxActiveEvents', 'maxActiveEventsPerDay', 'maxActiveEventsPerHour',
	];

	/**
	 * SuperCampaign constructor.
	 *
	 * @param AdFox $adfox
	 * @param array $attributes
	 */
	public function __construct(AdFox $adfox, $attributes)
	{
		$this->id = $attributes['ID'];
		$this->status = $attributes['status'];
		$this->setImpressionsLimits($attributes['maxImpressions'], $attributes['maxImpressionsPerDay'], $attributes['maxImpressionsPerHour']);
		$this->setClicksLimits($attributes['maxClicks'], $attributes['maxClicksPerDay'], $attributes['maxClicksPerHour']);
		$this->setActiveEventsLimits($attributes['maxActiveEvents'], $attributes['maxActiveEventsPerDay'], $attributes['maxActiveEventsPerHour']);

		parent::__construct($adfox);
	}

	/**
	 * Set AdFox instance to send requests to
	 *
	 * @param AdFox $adFox
	 */
	public function setAdfox(AdFox $adFox)
	{
		$this->adfox = $adFox;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getType()
	{
		return AdFox::OBJECT_CAMPAIGN;
	}
}