<?php

namespace AdFox;

use AdFox\Campaigns\SuperCampaign;

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
	const CODE_API_CALL_ERROR = 60;

	const OBJECT_ACCOUNT = 'account';

	const ACTION_LIST = 'list';

	const ACTION_OBJECT_SUPERCAMPAIGN = 'superCampaign';

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
	protected function callApi($object, $action, $actionObject, $parameters = [])
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
			throw new AdfoxException((string) $response->status->error, (int) $response->status->code);
		}

		return $response->result;
	}

	/**
	 * Find SuperCampaign by ID
	 *
	 * @param $id
	 * @return SuperCampaign
	 * @throws AdfoxException
	 */
	public function findSuperCampaign($id)
	{
		$response = $this->callApi(self::OBJECT_ACCOUNT, self::ACTION_LIST, self::ACTION_OBJECT_SUPERCAMPAIGN, ['actionObjectID' => $id]);
		
		return new SuperCampaign($this, (array) $response->data->row0);
	}
}