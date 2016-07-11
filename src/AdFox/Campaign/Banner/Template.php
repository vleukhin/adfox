<?php
/**
 * Created by PhpStorm.
 * User: vleukhin
 * Date: 04.07.2016
 * Time: 18:27
 */

namespace AdFox\Campaign\Banner;


use AdFox\AdFox;
use AdFox\BaseObject;

class Template extends BaseObject{

	/**
	 * Banner type of this template
	 *
	 * @var Type
	 */
	public $bannerType;

	/**
	 * Template name
	 *
	 * @var string
	 */
	public $name;

	/**
	 * Attributes that can be modified
	 *
	 * @var array
	 */
	protected $attributes = ['id', 'name'];

	/**
	 * Banner template constructor.
	 *
	 * @param AdFox $adFox
	 * @param array $attributes
	 * @param array $relations
	 */
	public function __construct(AdFox $adFox, $attributes, $relations = [])
	{
		parent::__construct($adFox);

		$this->id = $attributes['templateID'];
		$this->name = $attributes['templateName'];

		$this->loadRelations($relations);
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