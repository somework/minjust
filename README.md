Example

```$xslt
<?php

require __DIR__ . '/vendor/autoload.php';

$dom = new \PHPHtmlParser\Dom();
$parser = new \BreviManu\Minjust\Parser($dom, [
    new \BreviManu\Minjust\Strategy\RewindParseStrategy(),
    new \BreviManu\Minjust\Strategy\NoPaginationParseStrategy(),
    new \BreviManu\Minjust\Strategy\NoRewindParseStrategy(),
]);

$client = new \GuzzleHttp\Client();
$service = new \BreviManu\Minjust\Service($client, $parser);

$findRequest = new \BreviManu\Minjust\FindRequest();
$findRequest->setMax(100);
$response = $service->findAll($findRequest);
```

Не работает:
- Получение по имени
- Получение по значениям включающим русские символы

Рекомендую не использовать фильтрацию - сильно увеличивает время ответа от сервиса минюста

Источник данных: http://lawyers.minjust.ru/Lawyers