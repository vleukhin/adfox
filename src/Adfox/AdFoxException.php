<?php

namespace AdFox;

use \Exception;

class AdfoxException extends Exception{

	/**
	 * Request params caused this exception
	 *
	 * @var array
	 */
	protected $request;

	/**
	 * AdfoxException constructor.
	 *
	 * @param string $message
	 * @param int $code
	 * @param Exception $previous
	 * @param array $request
	 */
	public function __construct($message = '', $code = 0, Exception $previous = null, $request = [])
	{
		$this->request = $request;

		parent::__construct($message, $code, $previous);
	}

	/**
	 * Get request params caused this exception
	 *
	 * @return array
	 */
	public function getRequest()
	{
		return $this->request;
	}
}
