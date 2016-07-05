<?php

require '../src/AdFox/Adfox.php';
require '../src/AdFox/AdFoxException.php';
require '../src/AdFox/Campaign/Traits/HasLevel.php';
require '../src/AdFox/Campaign/Traits/HasStatus.php';
require '../src/AdFox/Campaign/Traits/Restrictions/HasActiveEventsRestrictions.php';
require '../src/AdFox/Campaign/Traits/Restrictions/HasImpressionsRestrictions.php';
require '../src/AdFox/Campaign/Traits/Restrictions/HasClicksRestrictions.php';
require '../src/AdFox/Campaign/BaseObject.php';
require '../src/AdFox/Campaign/Campaign.php';
require '../src/AdFox/Campaign/Flight.php';
require '../src/AdFox/Campaign/Banner/Banner.php';
require '../src/AdFox/Campaign/Banner/Type.php';
require '../src/AdFox/Campaign/Banner/Template.php';


function dd($var){
	var_dump($var);exit;
}

$adfox = new \AdFox\AdFox('relevantmedia', 'rele_2016!');

$flight = $adfox->findFlight(588502);

$template = $adfox->findBannerTypeByName('BackGround (асинх.)')->findTemplate('BackGround WShifter // KP');

$banner = \AdFox\Campaigns\Banner\Banner::make($adfox, 'new tewst!', $template, [
	'user2' => 'test',
	'user11' => 'http://php.net/manual/ru/simplexmlelement.children.php',
	'trackingURL' => 'asd',
])->setClicksLimits(10000);

//$banner->setStatus(\AdFox\AdFox::OBJECT_STATUS_PAUSED)->setImpressionsLimits(500, 100, 10)->addToFlight($flight);
//$banner = $adfox->findBanner(1724257)->setParam('user5', '55%')->save();

$flight->addBanner($banner);