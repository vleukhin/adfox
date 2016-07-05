<?php

namespace AdFox\Site;

use AdFox\AdFox;
use AdFox\BaseObject;
use AdFox\Campaigns\Banner\Type as BannerType;

class Place extends BaseObject{

	public $name;

	/**
	 * Id of the banner type located in this place
	 *
	 * @var int
	 */
	protected $bannerTypeID;

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
	protected $siteID;

	/**
	 * Site this place is assign to
	 *
	 * @var Site
	 */
	public $site;

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
		$place = new self($adfox);

		$place->id = $attributes['ID'];
		$place->name = $attributes['name'];
		$place->siteID = $attributes['siteID'];
		$place->bannerTypeID = $attributes['bannerTypeID'];

		$place->loadRelations($relations);

		return $place;
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
}