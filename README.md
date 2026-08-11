[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/somework/minjust/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/somework/minjust/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/somework/minjust/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/somework/minjust/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/somework/minjust/badges/build.png?b=master)](https://scrutinizer-ci.com/g/somework/minjust/build-status/master)

Example

```$xslt
<?php

require __DIR__ . '/vendor/autoload.php';

$parser = new \SomeWork\Minjust\Parser\DomParser();
$client = new \SomeWork\Minjust\Client(
    new \DivineOmega\Psr18GuzzleAdapter\Client(),
    new \Http\Factory\Guzzle\RequestFactory(),
    new \Http\Factory\Guzzle\StreamFactory()
);

$service = new \SomeWork\Minjust\Service($client, $parser);

$findRequest = new \SomeWork\Minjust\FindRequest();
$findRequest->setMax(100);
$response = $service->findAll($findRequest);
```

Источник данных: http://lawyers.minjust.ru/Lawyers

## Maintained by

[Igor Pinchuk](https://github.com/somework) — I work on legacy PHP and Symfony systems:
audits, rescue and refactoring, performance. My open-source packages have been installed
over 70,000 times on Packagist.

Inherited a codebase nobody wants to touch? Written questions get written answers —
no discovery call required.
[i.pinchuk.work@gmail.com](mailto:i.pinchuk.work@gmail.com) ·
[LinkedIn](https://www.linkedin.com/in/kolgarn/)
