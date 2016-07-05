<?php

namespace AdFox\Campaigns\Banner;

use AdFox\AdFox;
use AdFox\Campaigns\BaseObject;

class Type extends BaseObject{

	/**
	 * BannerType name
	 *
	 * @var string
	 */
	public $name;

	/**
	 * BannerType constructor.
	 *
	 * @param AdFox $adFox
	 * @param array $attributes
	 * @param array $relations
	 */
	public function __construct(AdFox $adFox, $attributes, $relations = [])
	{
		parent::__construct($adFox);
		$this->loadRelations($relations);

		$this->id = $attributes['ID'];
		$this->name = $attributes['name'];
	}

	/**
	 * Get Object type. String constant from AdFox class.
	 *
	 * @return string
	 */
	protected function getType()
	{
		return AdFox::OBJECT_BANNER_TYPE;
	}
}