<?php

namespace AdFox;

/**
 * AdFox API wrapper class.
 */
class AdFox {

	/**
	 * API URL to send requests to.
	 *
	 * @var string
	 */
	protected $apiUrl = 'https://login.adfox.ru/API.php';

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
	 * AdFox constructor.
	 *
	 * @param $login
	 * @param $password
	 */
	public function __construct($login, $password)
	{
		$this->login = $login;
		$this->password = $password;
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
}