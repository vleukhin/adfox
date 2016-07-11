<?php

namespace AdFox\Campaign;

use AdFox\AdFox;
use AdFox\BaseObject;
use AdFox\Campaign\Traits\Restrictions\HasActiveEventsRestrictions;
use AdFox\Campaign\Traits\Restrictions\HasClicksRestrictions;
use AdFox\Campaign\Traits\Restrictions\HasDateRestrictions;
use AdFox\Campaign\Traits\Restrictions\HasImpressionsRestrictions;
use AdFox\Campaign\Traits\HasStatus;

class Campaign extends BaseObject{

	use HasStatus;
	use HasActiveEventsRestrictions;
	use HasClicksRestrictions;
	use HasImpressionsRestrictions;
	use HasDateRestrictions;

	/**
	 * Campaign name
	 *
	 * @var string
	 */
	public $name;

	/**
	 * Attributes that can be modified
	 *
	 * @var array
	 */
	protected $attributes = [
		'id', 'status', 'name',
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
		$this->name = $attributes['name'];
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