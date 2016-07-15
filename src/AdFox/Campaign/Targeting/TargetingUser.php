<?php

namespace AdFox\Campaign\Targeting;

use AdFox\AdFox;
use AdFox\Campaign\Targeting\Contracts\Targeting;

class TargetingUser implements Targeting {
	/**
	 * User targeting criteria id
	 *
	 * @var
	 */
	protected $criteriaId;

	/**
	 * Keys for this criteria
	 *
	 * @var array
	 */
	protected $keys = [];

	/**
	 * Indicates if all keys was loaded from API and seted to null
	 *
	 * @var bool
	 */
	protected $allKeysLoaded = false;

	/**
	 * Adfox instance to call API
	 *
	 * @var AdFox
	 */
	protected $adfox;

	/**
	 * TargetingUser constructor.
	 *
	 * @param AdFox $adfox
	 * @param $criteriaId
	 */
	public function __construct(AdFox $adfox, $criteriaId)
	{
		$this->adfox = $adfox;
		$this->criteriaId = $criteriaId;
	}

	/**
	 * Enable all keys
	 *
	 * @return $this
	 */
	public function enableAll()
	{
		return $this->enableOrDisableAll(true);
	}

	/**
	 * Disable all keys
	 *
	 * @return $this
	 */
	public function disableAll()
	{
		return $this->enableOrDisableAll(false);
	}

	/**
	 * Enable or disable all keys
	 *
	 * @return $this
	 */
	protected function enableOrDisableAll($enable)
	{
		$this->loadAllKeys();

		foreach ($this->keys as $key => $enabled)
		{
			$this->keys[$key] = $enable;
		}

		return $this;
	}

	/**
	 * Enable key
	 *
	 * @param int|string $key
	 * @return $this
	 */
	public function enable($key)
	{
		return $this->enableOrDisableKey($key, true);
	}

	/**
	 * Disable key
	 *
	 * @param int|string $key
	 * @return $this
	 */
	public function disable($key)
	{
		return $this->enableOrDisableKey($key, false);
	}

	/**
	 * Enable or disable key
	 *
	 * @param $key
	 * @param bool $enable
	 * @return $this
	 */
	protected function enableOrDisableKey($key, $enable)
	{
		$this->keys[$this->getIdByUserId($key)] = $enable;

		return $this;
	}

	/**
	 * Load all kets from API
	 */
	protected function loadAllKeys()
	{
		if (!$this->allKeysLoaded)
		{
			$this->adfox->callApiCallbackLoop(function ($criteria) {
					if (!isset($this->keys[$criteria['ID']]))
					{
						$this->keys[$criteria['ID']] = null;
					}
				},
				AdFox::OBJECT_USERCRITERIA, 'listValues', null, ['criteriaID' => $this->criteriaId, 'limit' => 999]
			);

			/**
			 * Undefined/Unknown criteria value
			 */
			if (!isset($this->keys[0]))
			{
				$this->keys[0] = null;
			}

			$this->allKeysLoaded = true;
		}
	}

	/**
	 * Gets id by user id
	 * If not found undefined/unkown id (0) will be used
	 *
	 * @param int|string $id
	 * @return int|null
	 * @throws \AdFox\AdfoxException
	 */
	protected function getIdByUserId($id)
	{
		$response = $this->adfox->callApi(AdFox::OBJECT_USERCRITERIA, 'listValues', null, ['criteriaID' => $this->criteriaId, 'limit' => 999]);

		if (!empty($response->data))
		{
			foreach ($response->data->children() as $criteria)
			{
				if ((string) $criteria->userID == $id)
				{
					return (int) $criteria->ID;
				}
			}
		}

		return 0;
	}

	/**
	 * Get Object type. String constant from Targeting class.
	 *
	 * @return string
	 */
	public function getType()
	{
		return Targeting::TYPE_USER;
	}

	/**
	 * Get params of this targeting
	 *
	 * @return array
	 */
	public function getParams()
	{
		$params  = ['criteriaId' => $this->criteriaId];

		foreach ($this->keys as $key => $enabled)
		{
			if (!is_null($enabled))
			{
				$params['v' . $key] = (int) $enabled;
			}
		}

		return $params;
	}
}