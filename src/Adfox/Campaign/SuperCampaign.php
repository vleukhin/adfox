<?php

namespace AdFox\Campaigns;

use AdFox\AdFox;

class SuperCampaign {

	const STATUS_ACTIVE = 0;
	const STATUS_PAUSED = 1;
	const STATUS_COMPLETED = 2;

	/**
	 * Adfox lib instance
	 *
	 * @var AdFox
	 */
	protected $adfox = null;

	/**
	 * Статус рекламной кампании
	 *
	 * @var string
	 */
	protected $status = null;

	/**
	 * SuperCampaign constructor.
	 *
	 * @param AdFox $adfox
	 * @param array $attributes
	 */
	public function __construct(AdFox $adfox, $attributes)
	{
		$this->adfox = $adfox;
		$this->status = $attributes['status'];
	}

	/**
	 * Check if SuperCampaign is active
	 *
	 * @return bool
	 */
	public function isAcitve()
	{
		return $this->status == self::STATUS_ACTIVE;
	}

	/**
	 * Check if supercampaign is paused
	 *
	 * @return bool
	 */
	public function isPaused()
	{
		return $this->status == self::STATUS_PAUSED;
	}

	/**
	 * Check if supercampaign is completed
	 *
	 * @return bool
	 */
	public function isCompleted()
	{
		return $this->status == self::STATUS_COMPLETED;
	}
}