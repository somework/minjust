<?php

namespace SomeWork\Minjust;

use PHPHtmlParser\Dom;
use SomeWork\Minjust\Entity\DetailLawyer;
use SomeWork\Minjust\Entity\Lawyer;

class Parser
{
    /**
     * @var \SomeWork\Minjust\ParserInterface
     */
    private $parser;

    public function __construct(ParserInterface $parseStrategy)
    {
        $this->parser = $parseStrategy;
    }

    /**
     * @param string $body
     *
     * @return \SomeWork\Minjust\FindResponse
     * @throws \PHPHtmlParser\Exceptions\ChildNotFoundException
     * @throws \PHPHtmlParser\Exceptions\CircularException
     * @throws \PHPHtmlParser\Exceptions\CurlException
     * @throws \PHPHtmlParser\Exceptions\StrictException
     */
    public function listResponse(string $body): FindResponse
    {
        $dom = new Dom();
        $dom->load($body);

        $findResponse = new FindResponse();
        $findResponse
            ->setPage($this->parser->getCurrentPage($dom))
            ->setTotalPage($this->parser->getTotalPage($dom))
            ->setElements($this->parser->getListLawyers($dom));

        return $findResponse;
    }

    public function detailLawyer(Lawyer $lawyer, string $body): DetailLawyer
    {
        $dom = new Dom();
        $dom->load($body);

        return $this
            ->parser
            ->getFullLawyer($dom)
            ->loadFromLawyer($lawyer);
    }
}
