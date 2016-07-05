<?php

namespace AdFox\Campaigns\Banner;

use AdFox\AdFox;
use AdFox\Campaigns\BaseObject;
use AdFox\Campaigns\Traits\Restrictions\HasClicksRestrictions;
use AdFox\Campaigns\Traits\Restrictions\HasImpressionsRestrictions;
use AdFox\Campaigns\Traits\HasStatus;

class Banner extends BaseObject{

	use HasStatus;
	use HasClicksRestrictions;
	use HasImpressionsRestrictions;

	/**
	 * Attributes that can be modified
	 *
	 * @var array
	 */
	protected $attributes = [
		'id', 'status', 'campaignId',
		'maxImpressions', 'maxImpressionsPerDay', 'maxImpressionsPerHour',
		'maxClicks', 'maxClicksPerDay', 'maxClicksPerHour',
	];


	/**
	 * Flight ID this banner is assign to
	 *
	 * @var int
	 */
	protected $campaignID;

	/**
	 * Banner constructor.
	 *
	 * @param AdFox $adfox
	 * @param array $attributes
	 * @param array $relations
	 */
	public function __construct(AdFox $adfox, $attributes, $relations = [])
	{
		parent::__construct($adfox);

		$this->id = $attributes['ID'];
		$this->status = $attributes['status'];
		$this->campaignID = $attributes['campaignID'];
		$this->setImpressionsLimits($attributes['maxImpressions'], $attributes['maxImpressionsPerDay'], $attributes['maxImpressionsPerHour']);
		$this->setClicksLimits($attributes['maxClicks'], $attributes['maxClicksPerDay'], $attributes['maxClicksPerHour']);

		$this->loadRelations($relations);
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getType()
	{
		return AdFox::OBJECT_BANNER;
	}
}