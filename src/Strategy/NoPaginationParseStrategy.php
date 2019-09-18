<?php

namespace SomeWork\Minjust\Strategy;

use PHPHtmlParser\Dom;

class NoPaginationParseStrategy implements ParseStrategyInterface
{
    public function getPage(Dom $dom): int
    {
        return 1;
    }

    public function getTotalPage(Dom $dom): int
    {
        return 1;
    }

    public function isSupport(Dom $dom): bool
    {
        return $dom->find('span.summ > div.pagination > a')->count() === 0;
    }
}
