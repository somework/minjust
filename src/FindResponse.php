<?php

namespace SomeWork\Minjust;

class FindResponse
{
    /**
     * @var \SomeWork\Minjust\Entity\Lawyer[]
     */
    protected $elements = [];

    /**
     * @var \Generator|\SomeWork\Minjust\Entity\FullLawyer[]
     */
    protected $fullElements;

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

    /**
     * @return \Generator|\SomeWork\Minjust\Entity\FullLawyer[]
     */
    public function getFullElements()
    {
        return $this->fullElements;
    }

    /**
     * @param \Generator|\SomeWork\Minjust\Entity\FullLawyer[] $fullElements
     *
     * @return static
     */
    public function setFullElements(\Generator $fullElements): self
    {
        $this->fullElements = $fullElements;

        return $this;
    }
}
