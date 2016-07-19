<?php

namespace AdFox;

use AdFox\Campaign\Banner\Banner;
use AdFox\Campaign\Banner\Type as BannerType;
use AdFox\Campaign\Campaign;
use AdFox\Campaign\Flight;
use AdFox\Site\Site;
use ReflectionClass;

/**
 * AdFox API wrapper class.
 */
class AdFox {

	/**
	 * API URL to send requests to.
	 *
	 * @var string
	 */
	protected $apiUrl = 'https://api.adfox.ru/v1/API.php';

	/**
	 * Base AdFox GUI URL
	 *
	 * @var string
	 */
	public $baseUrl = 'https://login.adfox.ru/';

	/**
	 * User login.
	 *
	 * @var string
	 */
	protected $login = null;

	/**
	 * User password.
	 *
	 * @var string
	 */
	protected $password = null;

	/**
	 * API response codes
	 */
	const CODE_NO_ERROR = 0;
	const CODE_AUTH_ERROR = -1;
	const CODE_PARAM_INCORRECT = -6;
	const CODE_PARAM_MISSING = -7;
	const CODE_PARAM_EMPTY = -9;
	const CODE_API_CALL_ERROR = 60;

	/**
	 * API objects
	 */
	const OBJECT_ACCOUNT = 'account';
	const OBJECT_CAMPAIGN = 'superCampaign';
	const OBJECT_FLIGHT = 'campaign';
	const OBJECT_BANNER = 'banner';
	const OBJECT_BANNER_TYPE = 'bannerType';
	const OBJECT_BANNER_TEMPLATE = 'template';
	const OBJECT_SITE = 'website';
	const OBJECT_PLACE = 'place';
	const OBJECT_TARGETING = 'targeting';
	const OBJECT_USERCRITERIA = 'userCriteria';

	/**
	 * API actions
	 */
	const ACTION_INFO = 'info';
	const ACTION_LIST = 'list';
	const ACTION_ADD = 'add';
	const ACTION_MODIFY = 'modify';
	const ACTION_PLACE = 'placing';
	const ACTION_TARGET = 'target';
	const ACTION_UPLOAD = 'upload';

	/**
	 * Objects statuses
	 */
	const OBJECT_STATUS_ACTIVE = 0;
	const OBJECT_STATUS_PAUSED = 1;
	const OBJECT_STATUS_COMPLETED = 2;

	/**
	 * Smooth types
	 */
	const SMOOTH_MAX = 0;
	const SMOOTH_STEADY_DAY = 1;
	const SMOOTH_STEADY_ALL = 2;
	const SMOOTH_STEADY_ALL_AUTO = 3;

	const DATE_FORMAT = 'Y-m-d H:i';

	/**
	 * AdFox constructor.
	 *
	 * @param $login
	 * @param $password
	 * @param bool $debug
	 */
	public function __construct($login, $password, $debug = false)
	{
		$this->debug = $debug;
		$this->login = $login;
		$this->password = hash('sha256', $password);
	}

	/**
	 * Set API URL to send requests to.
	 * 
	 * @param $url
	 * @return $this
	 */
	public function setApiUrl($url)
	{
		$this->apiUrl = $url;

		return $this;
	}

	/**
	 * Call Adfox API
	 *
	 * @param string $object
	 * @param string $action
	 * @param string $actionObject
	 * @param array $parameters
	 * @return \SimpleXMLElement[]
	 * @throws AdfoxException
	 */
	public function callApi($object, $action, $actionObject = null, $parameters = [])
	{
		$request = [
			'loginAccount' => $this->login,
			'loginPassword' => $this->password,
			'object' => $object,
			'action' => $action,
			'actionObject' => $actionObject,
			'encoding' => 'UTF-8',
		];

		$request += $parameters;

		$curl = curl_init($this->apiUrl);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $request);

		$response = curl_exec($curl);

		if ($this->debug)
		{
			$info = curl_getinfo($curl);
			echo '-------------------------------' . PHP_EOL;
			echo '|  Send request to AdFox API  |' . PHP_EOL;
			echo '-------------------------------' . PHP_EOL;
			echo 'Request Content Length: ' .$info['upload_content_length'] . PHP_EOL;
			echo 'Request size: ' .$info['request_size'] . PHP_EOL;
			echo 'Time: ' .$info['total_time'] . PHP_EOL;
			echo 'Response HTTP code: ' . $info['http_code'] . PHP_EOL;
			echo 'Params:' . PHP_EOL;
			print_r($request);
		}

		if ($response == false)
		{
			throw new AdfoxException(curl_error($curl), static::CODE_API_CALL_ERROR, null, $request);
		}

		$response = new \SimpleXMLElement($response);

		if (empty($response))
		{
			throw new AdfoxException('Empty AdFox response', static::CODE_API_CALL_ERROR, null, $request);
		}
		elseif((string) $response->status->code != static::CODE_NO_ERROR)
		{
			$message = (string) $response->status->error;

			if (in_array($response->status->code, [static::CODE_PARAM_MISSING, static::CODE_PARAM_EMPTY, static::CODE_PARAM_INCORRECT]))
			{
				$message .= ': ' . (string) $response->status->parameter;
			}

			throw new AdfoxException($message, (int) $response->status->code, null, $request);
		}

		return $response->result ? $response->result : $response->status;
	}

	/**
	 * Find SuperCampaign by ID
	 *
	 * @param $id
	 * @param array $relations
	 * @return Campaign|null
	 */
	public function findCampaign($id, $relations = [])
	{
		if ($attributes = $this->findObject(static::OBJECT_CAMPAIGN, $id))
		{
			return new Campaign($this, (array) $attributes, $relations);
		}

		return null;
	}

	/**
	 * Find Campaign by ID
	 *
	 * @param $id
	 * @param array $relations relations to load
	 * @return Flight|null
	 */
	public function findFlight($id, $relations = [])
	{
		if ($attributes = $this->findObject(static::OBJECT_FLIGHT, $id))
		{
			return new Flight($this, $attributes, $relations);
		}

		return null;
	}

	/**
	 * Find Banner by ID
	 *
	 * @param $id
	 * @param array $relations relations to load
	 * @return Banner|null
	 */
	public function findBanner($id, $relations = [])
	{
		if ($attributes = $this->findObject(static::OBJECT_BANNER, $id))
		{
			return Banner::createFromResponse($this, $attributes, $relations);
		}

		return null;
	}

	/**
	 * Find BannerType by id
	 *
	 * @param $id
	 * @param array $relations
	 * @return BannerType|null
	 */
	public function findBannerType($id, $relations = [])
	{
		if ($attributes = $this->findObject(static::OBJECT_BANNER_TYPE, $id))
		{
			return new BannerType($this, $attributes, $relations);
		}

		return null;
	}

	/**
	 * Find BannerType by name
	 *
	 * @param $name
	 * @param array $relations
	 * @return BannerType|false
	 * @throws AdfoxException
	 */
	public function findBannerTypeByName($name, $relations = [])
	{
		$response = $this->callApi(static::OBJECT_ACCOUNT, static::ACTION_LIST, static::OBJECT_BANNER_TYPE, ['limit' => 1000]);

		if (!empty($response->data))
		{
			foreach ($response->data->children() as $bannerType)
			{
				if ((string) $bannerType->name == $name)
				{
					return new BannerType($this, (array) $bannerType, $relations);
				}
			}
		}
		
		return false;
	}

	/**
	 * Make call to API and passes list results through given callback
	 *
	 * @param $callback
	 * @param $object
	 * @param $action
	 * @param null $actionObject
	 * @param array $parameters
	 * @throws AdfoxException
	 */
	public function callApiCallbackLoop($callback, $object, $action, $actionObject = null, $parameters = [])
	{
		$response = $this->callApi($object, $action, $actionObject, $parameters);

		if (!empty($response->data))
		{
			foreach ($response->data->children() as $children)
			{
				$callback((array) $children);
			}
		}
	}

	/**
	 * Find BannerType by id
	 *
	 * @param $id
	 * @param array $relations
	 * @return Site|null
	 */
	public function findSite($id, $relations = [])
	{
		if ($attributes = $this->findObject(static::OBJECT_SITE, $id))
		{
			return  new Site($this, $attributes, $relations);
		}

		return null;
	}

	/**
	 * Find BannerType by id
	 *
	 * @param $name
	 * @param array $relations
	 * @return Site|null
	 */
	public function findSiteByName($name, $relations = [])
	{
		$response = $this->callApi(static::OBJECT_ACCOUNT, static::ACTION_LIST, static::OBJECT_SITE, ['limit' => 1000]);

		if (!empty($response->data))
		{
			foreach ($response->data->children() as $site)
			{
				if ((string) $site->name == $name)
				{
					return new Site($this, (array) $site, $relations);
				}
			}
		}

		return null;
	}

	/**
	 * Find object of type by id
	 *
	 * @param $type
	 * @param $id
	 * @return array|bool
	 * @throws AdfoxException
	 */
	protected function findObject($type, $id)
	{
		$response = $this->callApi(static::OBJECT_ACCOUNT, static::ACTION_LIST, $type, ['actionObjectID' => $id]);

		if (!empty($response->data))
		{
			return (array) $response->data->row0;
		}

		return false;
	}

	/**
	 * Creates campaign
	 *
	 * @param string $name name of campaign
	 * @param int|string $advertiser id or name of advertiser
	 * @return Campaign
	 * @throws AdfoxException
	 */
	public function createCampaign($name, $advertiser)
	{
		if (!is_int($advertiser))
		{
			$this->callApiCallbackLoop(function($advertiserData) use (&$advertiser) {
				if ($advertiser == $advertiserData['account'])
				{
					$advertiser = (int) $advertiserData['ID'];
				}
			}, static::OBJECT_ACCOUNT, static::ACTION_LIST,  'advertiser');
		}

		if (!is_int($advertiser))
		{
			throw new AdfoxException('Can\'t find advertiser to create campaign');
		}

		$response = $this->callApi(static::OBJECT_ACCOUNT, static::ACTION_ADD, static::OBJECT_CAMPAIGN, ['name' => $name, 'advertiserID' => $advertiser]);

		return $this->findCampaign($response->ID);
	}

	/**
	 * Find campaigns by criterias
	 *
	 * @param string $name
	 * @param int $status
	 * @param string|int $dateAddedFrom
	 * @param string|int $dateAddedTo
	 *
	 * @return Campaign[]
	 */
	public function getCampaigns($name = null, $status = null, $dateAddedFrom = null, $dateAddedTo = null)
	{
		$campaigns = [];
		$params = ['limit' => 999];

		if (!empty($name))
		{
			$params['search'] = $name;
		}

		if (!is_null($dateAddedFrom))
		{
			$params['dateAddedFrom'] = static::convertDate($dateAddedFrom);
		}

		if (!is_null($dateAddedTo))
		{
			$params['dateAddedTo'] = static::convertDate($dateAddedTo);
		}

		if (!is_null($status))
		{
			if (!in_array($status, static::getConstants('OBJECT_STATUS')))
			{
				$status = static::OBJECT_STATUS_ACTIVE;
			}
		}

		$result = $this->callApi(static::OBJECT_ACCOUNT, static::ACTION_LIST, static::OBJECT_CAMPAIGN, $params);

		foreach ($result->data->children() as $campaign)
		{
			if (is_null($status) or (int) $campaign->status == $status)
			{
				/**
				 * @TODO Remove lib side name filtering
				 * Search param does not work in API now.
				 * So we are filtering by ourself
				 * Remove when it will come alive
				 */
				if (!isset($params['search']) or strpos((string) $campaign->name, $params['search']) !== false)
				{
					$campaigns[] = new Campaign($this, (array) $campaign);
				}
			}
		}

		return $campaigns;
	}

	/**
	 * Gets array of defined constants
	 *
	 * @param string $prefix filter by prefix
	 * @param string $class class to get consts from. defailt is AdFox
	 *
	 * @return array
	 */
	public static function getConstants($prefix = null, $class = null)
	{
		if (is_null($class))
		{
			$class = static::class;
		}

		$reflect = new ReflectionClass($class);
		$constants =  $reflect->getConstants();

		if (!is_null($prefix))
		{
			foreach ($constants as $name => $value)
			{
				if (strpos($name, $prefix) !== 0)
				{
					unset($constants[$name]);
				}
			}
		}

		return $constants;
	}

	/**
	 * Convert given date string to Adfox format
	 *
	 * @param string|int $date
	 * @return string
	 */
	public static function convertDate($date)
	{
		if (is_int($date))
		{
			$date = date(AdFox::DATE_FORMAT, $date);
		}
		elseif (!preg_match('@^\d{4}-\d{2}-\d{2}(\s\d{2}:\d{2}(:\d{2})?)?$@', $date))
		{
			$date = date(AdFox::DATE_FORMAT, strtotime($date));
		}

		return $date;
	}
}