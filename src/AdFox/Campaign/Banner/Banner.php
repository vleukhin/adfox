<?php

namespace AdFox\Campaigns\Banner;

use AdFox\AdFox;
use AdFox\BaseObject;
use AdFox\Campaigns\Flight;
use AdFox\Campaigns\Traits\Restrictions\HasClicksRestrictions;
use AdFox\Campaigns\Traits\Restrictions\HasDateRestrictions;
use AdFox\Campaigns\Traits\Restrictions\HasImpressionsRestrictions;
use AdFox\Campaigns\Traits\HasStatus;

class Banner extends BaseObject{

	use HasStatus;
	use HasClicksRestrictions;
	use HasImpressionsRestrictions;
	use HasDateRestrictions;

	/**
	 * Banner name
	 *
	 * @var string
	 */
	public $name;

	/**
	 * Banner template
	 *
	 * @var Template
	 */
	protected $template;

	/**
	 * Attributes that can be modified
	 *
	 * @var array
	 */
	protected $attributes = [
		'id', 'name', 'status', 'campaignId',
		'maxImpressions', 'maxImpressionsPerDay', 'maxImpressionsPerHour',
		'maxClicks', 'maxClicksPerDay', 'maxClicksPerHour',
		'user1', 'user2', 'user3', 'user4', 'user5', 'user6', 'user7', 'user8', 'user9', 'user10', 'user11', 'user12',
		'dateStart', 'dateEnd',
	];

	/**
	 * Attributes that can be set to null
	 *
	 * @var array
	 */
	protected $nullable = ['dateEnd'];

	/**
	 * Banner params
	 *
	 * @var array
	 */
	protected $params = [];

	/**
	 * Flight ID this banner is assign to
	 *
	 * @var int
	 */
	protected $campaignID;

	/**
	 * Banner constructor.
	 *
	 * @param AdFox $adfox
	 * @param array $attributes
	 * @param array $relations
	 *
	 * @return Banner
	 */
	public static function createFromResponse(AdFox $adfox, $attributes, $relations = [])
	{
		$banner = new self($adfox);

		$banner->id = $attributes['ID'];
		$banner->status = $attributes['status'];
		$banner->campaignID = $attributes['campaignID'];
		$banner->setImpressionsLimits($attributes['maxImpressions'], $attributes['maxImpressionsPerDay'], $attributes['maxImpressionsPerHour']);
		$banner->setClicksLimits($attributes['maxClicks'], $attributes['maxClicksPerDay'], $attributes['maxClicksPerHour']);
		$banner->setDateRestrictions($attributes['dateStart'], $attributes['dateEnd']);

		foreach ($attributes as $attribute => $value)
		{
			if (preg_match('@^parameter(\d)$@', $attribute, $matches))
			{
				$banner->setParam('user' . $matches[1], (string) $value);
			}
		}

		$banner->loadRelations($relations);

		return $banner;
	}

	/**
	 * Make banner instanse
	 *
	 * @param AdFox $adfox
	 * @param $name
	 * @param Template $template
	 * @param $params
	 *
	 * @return $this
	 */
	public static function make(AdFox $adfox, $name, Template $template, $params)
	{
		$banner = new self($adfox);
		$banner->name = $name;
		$banner->template = $template;
		$banner->setParams($params);

		return $banner;
	}

	/**
	 * Set banner params
	 *
	 * @param $params
	 * @return $this
	 */
	public function setParams($params)
	{
		$this->params = $params;

		return $this;
	}

	/**
	 * Set banner param
	 *
	 * @param $name
	 * @param $value
	 * @return $this
	 */
	public function setParam($name, $value)
	{
		$this->params[$name] = $value;

		return $this;
	}

	/**
	 * Get banner params
	 *
	 * @return array
	 */
	public function getParams()
	{
		return $this->params;
	}

	/**
	 * Get banner param
	 *
	 * @param $name
	 * @return mixed|null
	 */
	public function getParam($name)
	{
		if (isset($this->params[$name]))
		{
			return $this->params[$name];
		}

		return null;
	}

	/**
	 * Add this banner to flight
	 *
	 * @param Flight $flight
	 * @return Banner
	 * @throws \AdFox\AdfoxException
	 */
	public function addToFlight(Flight $flight)
	{
		$params = [
			'name' => $this->name,
			'campaignID' => $flight->id,
			'templateID' => $this->template->id,
		];

		$params = $params + $this->getParams() + $this->toArray();

		$response = $this->adfox->callApi(AdFox::OBJECT_ACCOUNT, AdFox::ACTION_ADD, AdFox::OBJECT_BANNER, $params);
		
		$banner = $this->adfox->findBanner($response->ID);

		return $banner;
	}

	/**
	 * {@inheritdoc}
	 */
	public function toArray()
	{
		return parent::toArray() + $this->getParams();
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getType()
	{
		return AdFox::OBJECT_BANNER;
	}
}