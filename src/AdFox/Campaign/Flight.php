<?php
/**
 * Created by PhpStorm.
 * User: vleukhin
 * Date: 04.07.2016
 * Time: 13:30
 */

namespace AdFox\Campaigns;

use AdFox\AdFox;
use AdFox\Campaigns\Banner\Template;
use AdFox\Campaigns\Traits\Restrictions\HasClicksRestrictions;
use AdFox\Campaigns\Traits\Restrictions\HasImpressionsRestrictions;
use AdFox\Campaigns\Traits\HasStatus;
use AdFox\Campaigns\Traits\HasLevel;

class Flight extends BaseObject{
	
	use HasStatus;
	use HasLevel;
	use HasClicksRestrictions;
	use HasImpressionsRestrictions;

	const ROTATION_PRIORY = 0;
	const ROTATION_PRECENT = 1;

	/**
	 * Campaign ID this filght is assign to
	 *
	 * @var int
	 */
	protected $superCampaignID;

	/**
	 * Attributes that can be modified
	 *
	 * @var array
	 */
	protected $attributes = [
		'id', 'status', 'level', 'superCampaignId',
		'maxImpressions', 'maxImpressionsPerDay', 'maxImpressionsPerHour',
		'maxClicks', 'maxClicksPerDay', 'maxClicksPerHour',
	];

	/**
	 * Campaign this filght is assign to
	 *
	 * @var Campaign
	 */
	public $campaign;

	/**
	 * Flight constructor.
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
		$this->level = $attributes['level'];
		$this->superCampaignID = $attributes['superCampaignID'];
		$this->setImpressionsLimits($attributes['maxImpressions'], $attributes['maxImpressionsPerDay'], $attributes['maxImpressionsPerHour']);
		$this->setClicksLimits($attributes['maxClicks'], $attributes['maxClicksPerDay'], $attributes['maxClicksPerHour']);
		
		$this->loadRelations($relations);
	}

	/**
	 * Load campaign this flight is assign to
	 *
	 * @return $this
	 */
	public function loadCampaign()
	{
		$this->campaign = $this->adfox->findCampaign($this->superCampaignID);

		return $this;
	}

	/**
	 * Creates banner
	 *
	 * @param null $name
	 * @param Template $template
	 * @param $bannerParams
	 *
	 * @return Flight|null
	 * @throws \AdFox\AdfoxException
	 */
	public function createBanner($name, Template $template, $bannerParams)
	{
		$params = [
			'name' => $name,
			'campaignID' => $this->id,
			'templateID' => $template->id,
		];

		$response = $this->adfox->callApi(AdFox::OBJECT_ACCOUNT, AdFox::ACTION_ADD, AdFox::OBJECT_BANNER, $params + $bannerParams);

		return $this->adfox->findBanner($response->ID);
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getType()
	{
		return AdFox::OBJECT_FLIGHT;
	}

	/**
	 * {@inheritdoc}
	 */
	public function toArray()
	{
		$array = parent::toArray();

		if (!is_null($this->campaign))
		{
			$array['campaign'] = $this->campaign->toArray();
		}

		return $array;
	}
}