<?php

namespace AdFox\Campaigns;

use AdFox\AdFox;
use AdFox\Campaigns\Traits\Restrictions\HasActiveEventsRestrictions;
use AdFox\Campaigns\Traits\Restrictions\HasClicksRestrictions;
use AdFox\Campaigns\Traits\Restrictions\HasDateRestrictions;
use AdFox\Campaigns\Traits\Restrictions\HasImpressionsRestrictions;
use AdFox\Campaigns\Traits\HasStatus;

class Campaign extends BaseObject{

	use HasStatus;
	use HasActiveEventsRestrictions;
	use HasClicksRestrictions;
	use HasImpressionsRestrictions;
	use HasDateRestrictions;

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
		'dateStart', 'dateEnd',
	];

	/**
	 * Attributes that can be set to null
	 *
	 * @var array
	 */
	protected $nullable = ['dateEnd'];

	/**
	 * Flights assigned to this campaign
	 *
	 * @var Flight[]
	 */
	public $flights = [];

	/**
	 * SuperCampaign constructor.
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
		$this->setImpressionsLimits($attributes['maxImpressions'], $attributes['maxImpressionsPerDay'], $attributes['maxImpressionsPerHour']);
		$this->setClicksLimits($attributes['maxClicks'], $attributes['maxClicksPerDay'], $attributes['maxClicksPerHour']);
		$this->setActiveEventsLimits($attributes['maxActiveEvents'], $attributes['maxActiveEventsPerDay'], $attributes['maxActiveEventsPerHour']);

		$this->loadRelations($relations);
	}

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
	protected function loadFlights()
	{
		$response = $this->adfox->callApi(AdFox::OBJECT_ACCOUNT, AdFox::ACTION_LIST, AdFox::OBJECT_FLIGHT, ['superCampaignID' => $this->id]);
		foreach ($response->data->children() as $flightData)
		{
			$this->flights[] = new Flight($this->adfox, (array) $flightData);
		}
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