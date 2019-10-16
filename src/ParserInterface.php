<?php

namespace SomeWork\Minjust;

use PHPHtmlParser\Dom;
use SomeWork\Minjust\Entity\DetailLawyer;

interface ParserInterface
{
    public function getCurrentPage(Dom $dom): int;

    public function getTotalPage(Dom $dom): int;

    public function getListLawyers(Dom $dom): array;

    public function getFullLawyer(Dom $dom): DetailLawyer;
}
