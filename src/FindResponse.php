<?php

declare(strict_types=1);

namespace SomeWork\Minjust;

use Generator;
use SomeWork\Minjust\Entity\DetailLawyer;
use SomeWork\Minjust\Entity\Lawyer;

/**
 * @see \SomeWork\Minjust\Tests\Unit\FindResponseTest
 */
class FindResponse
{
    /**
     * @var Lawyer[]
     */
    protected $lawyers = [];

    /**
     * @var Generator|DetailLawyer[]
     */
    protected $detailLawyers;

    /**
     * @var int
     */
    protected $page = 1;

    /**
     * @var int
     */
    protected $totalPage = 1;

    /**
     * @var int
     */
    protected $total = 0;

    /**
     * @return array
     */
    public function getLawyers(): array
    {
        return $this->lawyers;
    }

    /**
     * @param array $lawyers
     *
     * @return FindResponse
     */
    public function setLawyers(array $lawyers): FindResponse
    {
        $this->lawyers = $lawyers;

        return $this;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @param int $page
     *
     * @return FindResponse
     */
    public function setPage(int $page): FindResponse
    {
        $this->page = $page;

        return $this;
    }

    /**
     * @return int
     */
    public function getTotalPage(): int
    {
        return $this->totalPage;
    }

    /**
     * @param int $totalPage
     *
     * @return FindResponse
     */
    public function setTotalPage(int $totalPage): FindResponse
    {
        $this->totalPage = $totalPage;

        return $this;
    }

    /**
     * @return int
     */
    public function getTotal(): int
    {
        return $this->total;
    }

    /**
     * @param int $total
     *
     * @return FindResponse
     */
    public function setTotal(int $total): FindResponse
    {
        $this->total = $total;

        return $this;
    }

    /**
     * @param \SomeWork\Minjust\Entity\Lawyer $lawyer
     *
     * @return FindResponse
     */
    public function addLawyer(Lawyer $lawyer): self
    {
        $this->lawyers[] = $lawyer;

        return $this;
    }

    /**
     * @return Generator|DetailLawyer[]
     */
    public function getDetailLawyers()
    {
        return $this->detailLawyers;
    }

    /**
     * @param Generator|DetailLawyer[] $detailLawyers
     *
     * @return static
     */
    public function setDetailLawyersGenerator(Generator $detailLawyers): self
    {
        $this->detailLawyers = $detailLawyers;

        return $this;
    }
}
