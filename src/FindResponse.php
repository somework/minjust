<?php

namespace BreviManu\Minjust;

class FindResponse
{
    /**
     * @var array
     */
    protected $elements = [];

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
    public function getElements(): array
    {
        return $this->elements;
    }

    /**
     * @param array $elements
     *
     * @return FindResponse
     */
    public function setElements(array $elements): FindResponse
    {
        $this->elements = $elements;

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
     * @param array $element
     *
     * @return FindResponse
     */
    public function addElement(array $element): self
    {
        $this->elements[] = $element;

        return $this;
    }
}
