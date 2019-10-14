<?php

namespace SomeWork\Minjust\PaginationStrategy;

use PHPHtmlParser\Dom;

interface ParseStrategyInterface
{
    public function getPage(Dom $dom): int;

    public function getTotalPage(Dom $dom): int;

    public function isSupport(Dom $dom): bool;
}
