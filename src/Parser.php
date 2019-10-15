<?php

namespace SomeWork\Minjust;

use PHPHtmlParser\Dom;
use SomeWork\Minjust\Entity\FullLawyer;
use SomeWork\Minjust\Entity\LawFormation;
use SomeWork\Minjust\Entity\Lawyer;

class Parser
{
    /**
     * @var \SomeWork\Minjust\ListParserInterface
     */
    private $parseStrategy;

    public function __construct(ListParserInterface $parseStrategy)
    {
        $this->parseStrategy = $parseStrategy;
    }

    /**
     * @param string $body
     *
     * @return \SomeWork\Minjust\FindResponse
     * @throws \PHPHtmlParser\Exceptions\ChildNotFoundException
     * @throws \PHPHtmlParser\Exceptions\CircularException
     * @throws \PHPHtmlParser\Exceptions\CurlException
     * @throws \PHPHtmlParser\Exceptions\NotLoadedException
     * @throws \PHPHtmlParser\Exceptions\StrictException
     */
    public function buildListResponse(string $body): FindResponse
    {
        $dom = new Dom();
        $dom->load($body);

        $findResponse = new FindResponse();
        $findResponse
            ->setPage($this->parseStrategy->getCurrentPage($dom))
            ->setTotalPage($this->parseStrategy->getTotalPage($dom))
            ->setElements($this->parseStrategy->getListLawyers($dom));

        return $findResponse;
    }

    public function buildFullLawyer(Lawyer $lawyer, string $body): FullLawyer
    {
        $dom = new Dom();
        $dom->load($body);

        return $this
            ->getDetailLawyer($dom)
            ->loadFromLawyer($lawyer);
    }

    protected function getDetailLawyer(Dom $dom): FullLawyer
    {
        $nodes = $dom->find('.floating > p.row')->toArray();

        return (new FullLawyer())
            ->setLawFormation($this->getLawFormation($nodes))
            ->setChamberOfLaw(trim($nodes[5]->text()));
    }

    protected function getLawFormation(array $nodes): ?LawFormation
    {
        $formation = (new LawFormation())
            ->setOrganizationalForm($nodes[7]->text())
            ->setName($nodes[9]->text())
            ->setAddress($nodes[11]->text())
            ->setPhone($nodes[13]->text())
            ->setEmail($nodes[15]->text());

        return $formation->getOrganizationalForm() ? $formation : null;
    }
}
