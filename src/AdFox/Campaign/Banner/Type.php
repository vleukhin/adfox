<?php

namespace AdFox\Campaign\Banner;

use AdFox\AdFox;
use AdFox\BaseObject;

class Type extends BaseObject{
	
	/**
	 * Templates of this banner type
	 *
	 * @var Template[]
	 */
	public $templates = [];

	/**
	 * Loads templates of this banner type
	 *
	 * @throws \AdFox\AdfoxException
	 */
	protected function loadTemplates()
	{
		$response = $this->adfox->callApi(AdFox::OBJECT_BANNER_TYPE, AdFox::ACTION_LIST, AdFox::OBJECT_BANNER_TEMPLATE, ['objectID' => $this->id]);
		foreach ($response->data->children() as $templatetData)
		{
			$template = new Template($this->adfox, (array) $templatetData);
			$this->templates[] = $template;
		}

		return $this;
	}

	/**
	 * Find template of this type by id
	 *
	 * @param $name
	 * @return Template|null
	 */
	public function findTemplate($name)
	{
		if (empty($this->templates))
		{
			$this->loadTemplates();
		}

		foreach ($this->templates as $template)
		{
			if ($template->name == $name)
			{
				return $template;
			}
		}

		return null;
	}

	/**
	 * Get Object type. String constant from AdFox class.
	 *
	 * @return string
	 */
	protected function getType()
	{
		return AdFox::OBJECT_BANNER_TYPE;
	}

	/**
	 * Get URL of this type
	 *
	 * @return string
	 */
	protected function getUrl()
	{
		return $this->adfox->baseUrl . 'templates.php?bannerTypeID=' . $this->id;
	}
}