<?php

namespace AdFox;

use AdFox\Campaign\Targeting\Contracts\Targeting;

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
	 * BaseObject constructor.
	 *
	 * @param AdFox $adFox
	 */
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
		$attributes = $this->attributes;
		
		foreach (class_uses(static::class) as $trait)
		{
			$attributes = array_merge($attributes, call_user_func([$trait, 'get' . basename($trait) . 'Attributes']));
		}
		
		$array = [];

		foreach ($attributes as $property)
		{
			if (property_exists($this, $property))
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
		$relations = (array) $relations;
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
	 * Apply targeting to this object
	 *
	 * @param Targeting $targeting
	 * @throws AdfoxException
	 *
	 * @return $this
	 */
	public function applyTargeting(Targeting $targeting)
	{
		$params = ['objectID' => $this->id] + $targeting->getParams();
		$this->adfox->callApi($this->getType(), AdFox::ACTION_TARGET, $targeting->getType(), $params);

		return $this;
	}

	/**
	 * Get Object type. String constant from AdFox class.
	 *
	 * @return string
	 */
	abstract protected function getType();

	/**
	 * Get URL of this object
	 *
	 * @return string
	 */
	abstract protected function getUrl();
}