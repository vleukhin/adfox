<?php

namespace AdFox\Campaign;

use AdFox\AdFox;
use AdFox\BaseObject;
use AdFox\Campaign\Traits\Restrictions\HasActiveEventsRestrictions;
use AdFox\Campaign\Traits\Restrictions\HasClicksAndImpressions;
use AdFox\Campaign\Traits\Restrictions\HasDateRestrictions;
use AdFox\Campaign\Traits\HasStatus;

class Campaign extends BaseObject{

	use HasStatus;
	use HasActiveEventsRestrictions;
	use HasClicksAndImpressions;
	use HasDateRestrictions;
	
	/**
	 * Flights assigned to this campaign
	 *
	 * @var Flight[]
	 */
	public $flights = [];

	/**
	 * Creates flight
	 *
	 * @param null $name
	 * @return Flight|null
	 * @throws \AdFox\AdfoxException
	 */
	public function createFlight($name = null)
	{
		$response = $this->adfox->callApi(AdFox::OBJECT_ACCOUNT, AdFox::ACTION_ADD, AdFox::OBJECT_FLIGHT, [
			'name' => $name,
			'superCampaignID' => $this->id,
		]);

		return $this->adfox->findFlight($response->ID);
	}

	/**
	 * Load flights assigned to this campaign
	 *
	 * @throws \AdFox\AdfoxException
	 */
	public function loadFlights()
	{
		$response = $this->adfox->callApi(AdFox::OBJECT_ACCOUNT, AdFox::ACTION_LIST, AdFox::OBJECT_FLIGHT, ['superCampaignID' => $this->id]);
		foreach ($response->data->children() as $flightData)
		{
			$this->flights[] = new Flight($this->adfox, (array) $flightData);
		}

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getType()
	{
		return AdFox::OBJECT_CAMPAIGN;
	}

	/**
	 * Get URL of this campaign
	 *
	 * @return string
	 */
	protected function getUrl()
	{
		return $this->adfox->baseUrl . 'campaigns.php?superCampaignID=' . $this->id;
	}
}