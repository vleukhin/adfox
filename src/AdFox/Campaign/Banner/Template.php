<?php

namespace AdFox\Campaign\Banner;

use AdFox\AdFox;
use AdFox\BaseObject;

class Template extends BaseObject{
	
	/**
	 * Banner template constructor.
	 *
	 * @param AdFox $adFox
	 * @param array $attributes
	 * @param array $relations
	 */
	public function __construct(AdFox $adFox, $attributes, $relations = [])
	{
		$attributes['ID'] = $attributes['templateID'];
		$attributes['name'] = $attributes['templateName'];

		parent::__construct($adFox, $attributes, $relations);
	}

	/**
	 * Get Object type. String constant from AdFox class.
	 *
	 * @return string
	 */
	protected function getType()
	{
		return AdFox::OBJECT_BANNER_TEMPLATE;
	}

	/**
	 * Get URL of this object
	 *
	 * @return string
	 */
	protected function getUrl()
	{
		return $this->adfox->baseUrl . 'modifyTemplateForm.php?templateID=' . $this->id;
	}
}