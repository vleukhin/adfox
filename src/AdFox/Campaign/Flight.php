<?php

namespace AdFox\Campaign;

use AdFox\AdFox;
use AdFox\BaseObject;
use AdFox\Campaign\Banner\Banner;
use AdFox\Campaign\Banner\Template;
use AdFox\Campaign\Traits\Restrictions\HasClicksAndImpressions;
use AdFox\Campaign\Traits\Restrictions\HasClicksAndImpressionsSmooth;
use AdFox\Campaign\Traits\Restrictions\HasDateRestrictions;
use AdFox\Campaign\Traits\HasStatus;
use AdFox\Campaign\Traits\HasLevel;
use AdFox\Site\Place;
use AdFox\Site\Site;

class Flight extends BaseObject{
	
	use HasStatus;
	use HasLevel;
	use HasClicksAndImpressions;
	use HasClicksAndImpressionsSmooth;
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
	protected $attributes = ['id', 'name', 'superCampaignId'];

	/**
	 * Campaign this filght is assign to
	 *
	 * @var Campaign
	 */
	public $campaign;

	/**
	 * Banners of this flight
	 *
	 * @var Banner[]
	 */
	public $banners;

	/**
	 * Flight constructor.
	 *
	 * @param AdFox $adfox
	 * @param array $attributes
	 * @param array $relations
	 */
	public function __construct(AdFox $adfox, $attributes, $relations = [])
	{
		$this->superCampaignID = $attributes['superCampaignID'];

		parent::__construct($adfox, $attributes, $relations);
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
	 * Load this flight banners
	 *
	 * @return $this
	 */
	public function loadBanners()
	{
		$response = $this->adfox->callApi(AdFox::OBJECT_FLIGHT, AdFox::ACTION_LIST, AdFox::OBJECT_BANNER, ['objectID' => $this->id]);
		foreach ($response->data->children() as $banner)
		{
			$this->banners[] = Banner::createFromResponse($this->adfox, (array) $banner);
		}

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
	 * @param $type
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
	 * Get URL of this flight
	 *
	 * @return string
	 */
	public function getUrl()
	{
		return $this->adfox->baseUrl . 'banners.php?campaignID=' . $this->id;
	}
}