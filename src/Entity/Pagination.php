<?php

namespace SomeWork\Minjust\Entity;

class Pagination
{
    /**
     * @var int
     */
    protected $page = 1;

    /**
     * @var int
     */
    protected $totalPage = 1;

    public function __construct(int $page, int $totalPage)
    {
        $this->page = $page;
        $this->totalPage = $totalPage;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @return int
     */
    public function getTotalPage(): int
    {
        return $this->totalPage;
    }
}
