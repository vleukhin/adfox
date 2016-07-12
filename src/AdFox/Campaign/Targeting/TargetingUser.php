<?php

namespace AdFox\Campaign\Targeting;

use AdFox\AdFox;
use AdFox\Campaign\Targeting\Contracts\Targeting;

class TargetingUser implements Targeting
{
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
	protected $adFox;

	/**
	 * TargetingUser constructor.
	 *
	 * @param AdFox $adFox
	 * @param $criteriaId
	 */
	public function __construct(AdFox $adFox, $criteriaId)
	{
		$this->adFox = $adFox;
		$this->criteriaId = $criteriaId;
	}

	/**
	 * Enable all keys
	 *
	 * @return $this
	 */
	public function enableAll()
	{
		$this->loadAllKeys();

		foreach ($this->keys as $key => $enabled)
		{
			$this->keys[$key] = true;
		}

		return $this;
	}

	/**
	 * Disable all keys
	 *
	 * @return $this
	 */
	public function disableAll()
	{
		$this->loadAllKeys();

		foreach ($this->keys as $key => $enabled)
		{
			$this->keys[$key] = false;
		}

		return $this;
	}

	/**
	 * Enable key
	 *
	 * @param $key
	 * @return $this
	 */
	public function enable($key)
	{
		$this->keys[$key] = true;

		return $this;
	}

	/**
	 * Disable key
	 *
	 * @param $key
	 * @return $this
	 */
	public function disable($key)
	{
		$this->keys[$key] = false;

		return $this;
	}

	/**
	 * Load all kets from API
	 *
	 */
	protected function loadAllKeys()
	{
		if (!$this->allKeysLoaded)
		{
			$this->adFox->callApiCallbackLoop(function ($criteria) {
					$this->keys[$criteria['ID']] = null;
				},
				AdFox::OBJECT_USERCRITERIA, 'listValues', null, ['criteriaID' => $this->criteriaId, 'limit' => 999]
			);

			$this->allKeysLoaded = true;
		}
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
	 * @return mixed
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