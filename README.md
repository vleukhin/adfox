# Library to interact with AdFox advertisment system

## Supported Features
* Create flight/campaign
* Set impressions/clicks/events restrictions
* Change flight/campaign status and level
* Create/Editing banners

## Examples

#### Create and edit campaign
```
$adfox = new Adfox\Adfox('login', 'pass');

$campaign = $adfox->createCampaign('Test campaign');
$campaign->setLevel(3);
$campaign->setStatus(\AdFox\AdFox::OBJECT_STATUS_PAUSED);
$campaign->setImpressionsLimits(5000, 1000, 100);
$campaign->save();

$campaign->getMaxImpressions(); // 5000
$campaign->getMaxImpressionsPerDay(); // 1000
$campaign->getMaxImpressionsPerHour(); // 100

$campaign->isActive() // false
```

#### Create and edit flight
```
$adfox = new Adfox\Adfox('login', 'pass');

$campaign = $adfox->createCampaign('Test campaign', 'advertiserId');
$flight = $campaign->createFilght();

$flight->isActive() // true
$flight->setStatus(\AdFox\AdFox::OBJECT_STATUS_PAUSED)->save();
$flight->isActive() // false
$flight->isPaused() // true

$flight->setClicksLimits(5000, 1000)->save();
$flight->getMaxClicksPerDay(); // 1000
$flight->getMaxClicksPerHour(); // 0, no limit
```

#### Create and edit banners
```
$adfox = new Adfox\Adfox('login', 'pass');

$campaign = $adfox->createCampaign('Test campaign', 'advertiserId');
$flight = $campaign->createFilght();

$template = $adfox->findBannerTypeByName('BackGround')->findTemplate('BackGround WShifter');

$banner = \AdFox\Campaigns\Banner\Banner::make($adfox, 'New brand banner!', $template, [
	'trackingURL' => '//banners.adfox.ru/transparent.gif',
	'reference' => 'http://php.net',
	'user1' => '#fff',
])->setClicksLimits(10000)->setImpressionsLimits(500000);

$banner->addToFlight($flight);
// or
$flight->addBanner($banner);
```

```
$banner = $adfox->findBanner(1724257)->setParam('user5', '55%')->save();
```