<?php
/**
 * Created by PhpStorm.
 * User: vleukhin
 * Date: 04.07.2016
 * Time: 14:07
 */

namespace AdFox\Campaigns;

use AdFox\AdFox;

abstract class BaseObject {

	/**
	 * Object ID
	 *
	 * @var int
	 */
	public $id = null;

	/**
	 * Adfox lib instance
	 *
	 * @var AdFox
	 */
	protected $adfox = null;

	/**
	 * Attributes that can be modified
	 *
	 * @var array
	 */
	protected $attributes = [];

	/**
	 * Attributes that can be set to null
	 *
	 * @var array
	 */
	protected $nullable = [];

	public function __construct(AdFox $adFox)
	{
		$this->adfox = $adFox;
	}

	/**
	 * Set AdFox instance to send requests to
	 *
	 * @param AdFox $adFox
	 */
	public function setAdfox(AdFox $adFox)
	{
		$this->adfox = $adFox;
	}

	/**
	 * Get array represent of the Object.
	 *
	 * @return array
	 */
	public function toArray()
	{
		$array = [];

		foreach ($this->attributes as $property)
		{
			if (property_exists($this, $property) and (!is_null($this->{$property}) or in_array($property, $this->nullable)))
			{
				$array[$property] = $this->{$property};
			}
		}

		return $array;
	}

	/**
	 * Save Object.
	 *
	 * @throws \AdFox\AdfoxException
	 */
	public function save()
	{
		$parameters = ['objectID' => $this->id] + $this->toArray();
		$this->adfox->callApi($this->getType(), AdFox::ACTION_MODIFY, null, $parameters);

		return $this;
	}

	/**
	 * Loads relations if possible
	 *
	 * @param $relations
	 */
	protected function loadRelations($relations)
	{
		foreach ($relations as $relation)
		{
			$method = 'load'.ucfirst($relation);
			if (method_exists($this, $method))
			{
				$this->$method();
			}
		}
	}


	/**
	 * Get Object type. String constant from AdFox class.
	 *
	 * @return string
	 */
	abstract protected function getType();
}