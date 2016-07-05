<?php
/**
 * Created by PhpStorm.
 * User: vleukhin
 * Date: 04.07.2016
 * Time: 13:30
 */

namespace AdFox\Campaigns;

use AdFox\AdFox;
use AdFox\BaseObject;
use AdFox\Campaigns\Banner\Banner;
use AdFox\Campaigns\Banner\Template;
use AdFox\Campaigns\Traits\Restrictions\HasClicksRestrictions;
use AdFox\Campaigns\Traits\Restrictions\HasDateRestrictions;
use AdFox\Campaigns\Traits\Restrictions\HasImpressionsRestrictions;
use AdFox\Campaigns\Traits\HasStatus;
use AdFox\Campaigns\Traits\HasLevel;
use AdFox\Site\Place;
use AdFox\Site\Site;

class Flight extends BaseObject{
	
	use HasStatus;
	use HasLevel;
	use HasClicksRestrictions;
	use HasImpressionsRestrictions;
	use HasDateRestrictions;

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
		'dateStart', 'dateEnd',
	];

	/**
	 * Attributes that can be set to null
	 *
	 * @var array
	 */
	protected $nullable = ['dateEnd'];

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

		$banner = $this->adfox->findBanner($response->ID);
		$banner->setParams($bannerParams);

		return $banner;
	}

	/**
	 * Add banner to this flight
	 *
	 * @param Banner $banner
	 */
	public function addBanner(Banner $banner)
	{
		$banner->addToFlight($this);
	}

	/**
	 * Place this flight on the given place
	 *
	 * @param Place $place
	 *
	 * @return $this
	 */
	public function placeOnPlace(Place $place)
	{
		$this->place(AdFox::OBJECT_PLACE, $place->id);

		return $this;
	}

	/**
	 * Remove this flight from the given place
	 *
	 * @param Place $place
	 *
	 * @return $this
	 */
	public function removeFromPlace(Place $place)
	{
		$this->place(AdFox::OBJECT_PLACE, $place->id, true);

		return $this;
	}

	/**
	 * Place this flight on the given site
	 *
	 * @param Site $site
	 *
	 * @return $this
	 */
	public function placeOnSite(Site $site)
	{
		$this->place(AdFox::OBJECT_SITE, $site->id);

		return $this;
	}

	/**
	 * Remove this flight from the given site
	 *
	 * @param Site $site
	 *
	 * @return $this
	 */
	public function removeFromSite(Site $site)
	{
		$this->place(AdFox::OBJECT_SITE, $site->id, true);

		return $this;
	}

	/**
	 * Place/Remove this flight object on/from given placable object
	 *
	 * @param int $objectId
	 * @param bool $remove remove flag, default is false
	 * @throws \AdFox\AdfoxException
	 */
	protected function place($type, $objectId, $remove = false)
	{
		$this->adfox->callApi(AdFox::OBJECT_FLIGHT, AdFox::ACTION_PLACE, $type, [
			'actionStatus' => (int) !$remove,
			'objectID' => $this->id,
			'actionObjectID' => $objectId
		]);
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