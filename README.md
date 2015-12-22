The PHP SDK for Tencent XGPush
==============================

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]


The PHP SDK for Tencent XGPush

## Install

Via Composer

``` bash
$ composer require lokielse/tencent-xg-push
```

## Usage

```php
$client = new XGPush(<your_access_id>, <your_secret_key>);
$client->createMultiplePush(...)
$client->pushAllAndroid(...)
$client->queryTokenTags(...)
$client->pushAccountAndroid(...)
$client->pushAllDevices(...)
$client->batchDeleteTag(...)
$client->pushAllIOS(...)
$client->pushTagIOS(...)
$client->pushAccountMass(...)
$client->batchSetTag(...)
$client->pushTagAndroid(...)
$client->cancelTimingPush(...)
$client->queryTagTokenCount(...)
$client->queryDeviceCount(...)
$client->queryTokensOfAccount(...)
$client->queryInfoOfToken(...)
$client->pushTokenIOS(...)
$client->pushSingleAccount(...)
$client->pushDeviceMass(...)
$client->pushTags(...)
$client->queryTags(...)
$client->pushAccounts(...)
$client->pushAccountIOS(...)
$client->queryPushStatus(...)
```


## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email lokielse@gmail.com instead of using the issue tracker.

## Credits

- [Lokielse][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/lokielse/tencent-xg-push.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/lokielse/tencent-xg-push/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/lokielse/tencent-xg-push.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/lokielse/tencent-xg-push.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/lokielse/tencent-xg-push.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/lokielse/tencent-xg-push
[link-travis]: https://travis-ci.org/lokielse/tencent-xg-push
[link-scrutinizer]: https://scrutinizer-ci.com/g/lokielse/tencent-xg-push/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/lokielse/tencent-xg-push
[link-downloads]: https://packagist.org/packages/lokielse/tencent-xg-push
[link-author]: https://github.com/lokielse
[link-contributors]: ../../contributors
