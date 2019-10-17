<?php

namespace SomeWork\Minjust\Parser;

use SomeWork\Minjust\Entity\DetailLawyer;
use SomeWork\Minjust\FindResponse;

interface ParserInterface
{
    public function list(string $body): FindResponse;

    public function detail(string $body): DetailLawyer;
}
