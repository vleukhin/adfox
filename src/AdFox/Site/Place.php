<?php

namespace AdFox\Site;

use AdFox\AdFox;
use AdFox\BaseObject;
use AdFox\Campaign\Banner\Type as BannerType;

class Place extends BaseObject{

	/**
	 * Id of the banner type located in this place
	 *
	 * @var int
	 */
	public $bannerTypeID;

	/**
	 * Type of the banners located in this place
	 *
	 * @var BannerType
	 */
	public $bannerType;

	/**
	 * Site id this place is assign to
	 *
	 * @var int
	 */
	public $siteID;

	/**
	 * Site this place is assign to
	 *
	 * @var Site
	 */
	public $site;

	protected $attributes = ['id', 'name', 'siteID', 'bannerTypeID'];

	/**
	 * Place constructor
	 *
	 * @param AdFox $adfox
	 * @param $attributes
	 * @param array $relations
	 *
	 * @return Place
	 */
	public static function createFromResponse(AdFox $adfox, $attributes, $relations = [])
	{
		$place = new static($adfox, $attributes, $relations);
		$place->siteID = $attributes['siteID'];
		$place->bannerTypeID = $attributes['bannerTypeID'];

		return $place;
	}

	/**
	 * Loads banner type of this place
	 */
	public function loadBannerType()
	{
		$this->bannerType = $this->adfox->findBannerType($this->bannerTypeID);

		return $this;
	}

	/**
	 * Get Object type. String constant from AdFox class.
	 *
	 * @return string
	 */
	protected function getType()
	{
		return AdFox::OBJECT_PLACE;
	}

	/**
	 * Get URL of this object
	 *
	 * @return string
	 */
	protected function getUrl()
	{
		return $this->adfox->baseUrl . 'placeSummaryCampaignsPlacedForm.php?navigationTab=websites&placeID=' . $this->id;
	}
}