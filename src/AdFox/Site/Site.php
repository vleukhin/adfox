<?php

namespace AdFox\Site;

use AdFox\AdFox;
use AdFox\BaseObject;

class Site extends BaseObject{

	/**
	 * Places register on this site
	 *
	 * @var Place[]
	 */
	public $places = [];

	/**
	 * Find place on this site by name
	 *
	 * @param $name
	 * @return Place|null
	 */
	public function findPlaceByName($name)
	{
		if (empty($this->places))
		{
			$this->loadPlaces();
		}

		foreach ($this->places as $place)
		{
			if ($place->name == $name)
			{
				return $place;
			}
		}

		return null;
	}
	
	/**
	 * Loads this site places
	 *
	 * @throws \AdFox\AdfoxException
	 */
	public function loadPlaces()
	{
		$response = $this->adfox->callApi(AdFox::OBJECT_ACCOUNT, AdFox::ACTION_LIST, AdFox::OBJECT_PLACE, ['actionObjectID2' => $this->id]);
		foreach ($response->data->children() as $placeData)
		{
			$this->places[] = Place::createFromResponse($this->adfox, (array) $placeData);
		}

		return $this;
	}

	/**
	 * Get Object type. String constant from AdFox class.
	 *
	 * @return string
	 */
	protected function getType()
	{
		return \AdFox\AdFox::OBJECT_SITE;
	}

	/**
	 * Get URL of this site
	 *
	 * @return string
	 */
	protected function getUrl()
	{
		return $this->adfox->baseUrl . 'sections.php?navigationTab=websites&websiteID=' . $this->id;
	}
}