<?php

namespace BreviManu\Minjust\Strategy;

use BreviManu\Minjust\Entity\Lawyer;
use PHPHtmlParser\Dom;

interface ParseStrategyInterface
{
    public function getPage(Dom $dom): int;

    public function getTotalPage(Dom $dom): int;

    /**
     * @param Dom $dom
     *
     * @return Lawyer[]
     */
    public function getElements(Dom $dom): array;

    public function isSupport(Dom $dom): bool;
}
