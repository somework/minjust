Example

```$xslt
<?php

require __DIR__ . '/vendor/autoload.php';

$parser = new \SomeWork\Minjust\Parser(
    new \SomeWork\Minjust\PaginationStrategy\Pagination(),
);

$client = new \GuzzleHttp\Client();
$service = new \SomeWork\Minjust\Service($client, $parser);

$findRequest = new \SomeWork\Minjust\FindRequest();
$findRequest->setMax(100);
$response = $service->findAll($findRequest);
```

Не работает:
- Получение по имени
- Получение по значениям включающим русские символы

Рекомендую не использовать фильтрацию - сильно увеличивает время ответа от сервиса минюста

Источник данных: http://lawyers.minjust.ru/Lawyers