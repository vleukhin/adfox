<?php

namespace AdFox;

use AdFox\Campaigns\Campaign;
use AdFox\Campaigns\Flight;

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

	const CODE_NO_ERROR = 0;
	const CODE_AUTH_ERROR = -1;
	const CODE_PARAM_MISSING = -7;
	const CODE_API_CALL_ERROR = 60;

	const OBJECT_ACCOUNT = 'account';
	const OBJECT_CAMPAIGN = 'superCampaign';
	const OBJECT_FLIGHT = 'campaign';

	const ACTION_LIST = 'list';
	const ACTION_ADD = 'add';
	const ACTION_MODIFY = 'modify';

	const OBJECT_STATUS_ACTIVE = 0;
	const OBJECT_STATUS_PAUSED = 1;
	const OBJECT_STATUS_COMPLETED = 2;

	/**
	 * AdFox constructor.
	 *
	 * @param $login
	 * @param $password
	 */
	public function __construct($login, $password)
	{
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
	 * @param $object
	 * @param $action
	 * @param $actionObject
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
		];

		$request += $parameters;

		$curl = curl_init($this->apiUrl);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $request);

		$response = curl_exec($curl);

		if ($response == false)
		{
			throw new AdfoxException(curl_error($curl), self::CODE_API_CALL_ERROR);
		}

		$response = new \SimpleXMLElement($response);

		if (empty($response))
		{
			throw new AdfoxException('Empty AdFox response', self::CODE_API_CALL_ERROR);
		}
		elseif((string) $response->status->code != self::CODE_NO_ERROR)
		{
			$message = (string) $response->status->error;

			if ($response->status->code == self::CODE_PARAM_MISSING)
			{
				$message .= ': ' . (string) $response->status->parameter;
			}

			throw new AdfoxException($message, (int) $response->status->code);
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
		if ($attributes = $this->findObject(self::OBJECT_CAMPAIGN, $id))
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
		if ($attributes = $this->findObject(self::OBJECT_FLIGHT, $id))
		{
			return new Flight($this, $attributes, $relations);
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
	public function findObject($type, $id)
	{
		$response = $this->callApi(self::OBJECT_ACCOUNT, self::ACTION_LIST, $type, ['actionObjectID' => $id]);

		if (!empty($response->data))
		{
			return (array) $response->data->row0;
		}

		return false;
	}

	/**
	 * Creates campaign
	 *
	 * @param $name
	 * @param $advertiserId
	 * @return Campaign
	 * @throws AdfoxException
	 */
	public function createCampaign($name, $advertiserId)
	{
		$response = $this->callApi(self::OBJECT_ACCOUNT, self::ACTION_ADD, self::OBJECT_CAMPAIGN, ['name' => $name, 'advertiserID' => $advertiserId]);

		return $this->findCampaign($response->ID);
	}
}