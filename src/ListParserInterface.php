<?php

namespace SomeWork\Minjust;

use PHPHtmlParser\Dom;

interface ListParserInterface
{
    public function getCurrentPage(Dom $dom): int;

    public function getTotalPage(Dom $dom): int;

    public function getListLawyers(Dom $dom): array;
}
