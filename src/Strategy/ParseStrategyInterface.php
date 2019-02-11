<?php

namespace BreviManu\Minjust\Strategy;

use PHPHtmlParser\Dom;

interface ParseStrategyInterface
{
    public function getPage(Dom $dom): int;

    public function getTotalPage(Dom $dom): int;

    public function getElements(Dom $dom): array;

    public function isSupport(Dom $dom): bool;
}
