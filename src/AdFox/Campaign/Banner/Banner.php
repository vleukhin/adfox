<?php

namespace AdFox\Campaign\Banner;

use AdFox\AdFox;
use AdFox\BaseObject;
use AdFox\Campaign\Flight;
use AdFox\Campaign\Traits\Restrictions\HasClicksAndImpressions;
use AdFox\Campaign\Traits\Restrictions\HasDateRestrictions;
use AdFox\Campaign\Traits\HasStatus;

class Banner extends BaseObject{

	use HasStatus;
	use HasDateRestrictions;
	use HasClicksAndImpressions {
		setHasClicksAndImpressionsAttributes as traitSetHasClicksAndImpressionsAttributes;
	}

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
	protected $attributes = ['id', 'name', 'campaignID'];

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
		$banner = new static($adfox, $attributes, $relations);
		$banner->campaignID = $attributes['campaignID'];

		foreach ($attributes as $attribute => $value)
		{
			if (preg_match('@^parameter(\d)$@', $attribute, $matches))
			{
				$banner->setParam('user' . $matches[1], (string) $value);
			}
		}

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
	 * Set banner param as file
	 *
	 * @param $name
	 * @param string $filePath path on server or URL
	 * @return $this
	 */
	public function setFileParam($name, $filePath)
	{
		if ($this->campaignID)
		{
			$params = ['objectID' => $this->campaignID];

			if (filter_var($filePath, FILTER_VALIDATE_URL) !== false)
			{
				$params['URL'] = $filePath;
			}
			else
			{
				$params['file'] = new \CURLFile($filePath);
			}

			$result = $this->adfox->callApi(AdFox::OBJECT_FLIGHT, AdFox::ACTION_UPLOAD, null, $params);
			$this->params[$name] = (string) $result->value;
		}

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

	/**
	 * Get URL of this banner
	 *
	 * @return string
	 */
	public function getUrl()
	{
		return $this->adfox->baseUrl . 'modifyBannerForm.php?bannerID=' . $this->id;
	}

	/**
	 * Sets additional HasClicksAndImpressions attributes
	 *
	 * @param Banner $instance
	 * @param $attributes
	 */
	public static function setHasClicksAndImpressionsAttributes(self $instance, $attributes)
	{
		static::traitSetHasClicksAndImpressionsAttributes($instance, $attributes);

		$instance->clicks = $attributes['clicks'];
		$instance->clicksToday = $attributes['clicksToday'];
		$instance->clicksHour = $attributes['clicksHour'];

		$instance->impressions = $attributes['impressions'];
		$instance->impressionsToday = $attributes['impressionsToday'];
		$instance->impressionsHour = $attributes['impressionsHour'];
	}
}