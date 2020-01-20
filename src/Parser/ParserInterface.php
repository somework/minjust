<?php

declare(strict_types=1);

namespace SomeWork\Minjust\Parser;

use SomeWork\Minjust\Entity\DetailLawyer;
use SomeWork\Minjust\Entity\Pagination;

interface ParserInterface
{
    public function pagination(string $body): Pagination;

    public function detailLawyer(string $body): DetailLawyer;

    //    public function lawFormation(string $body): ?LawFormation;

    public function lawyers(string $body): array;

    public function locations(string $body): array;

    public function formOfLegalPractice(string $body): array;

    public function statuses(string $body): array;
}
