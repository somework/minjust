<?php

namespace SomeWork\Minjust\Parser;

use PHPHtmlParser\Dom;
use PHPHtmlParser\Dom\Collection;
use PHPHtmlParser\Dom\HtmlNode;
use PHPHtmlParser\Exceptions\ChildNotFoundException;
use PHPHtmlParser\Exceptions\NotLoadedException;
use SomeWork\Minjust\Entity\DetailLawyer;
use SomeWork\Minjust\Entity\LawFormation;
use SomeWork\Minjust\Entity\Lawyer;
use SomeWork\Minjust\FindResponse;

class DomParser implements ParserInterface
{
    public function list(string $body): FindResponse
    {
        $dom = (new Dom())->loadStr($body);

        $findResponse = new FindResponse();
        $findResponse
            ->setPage($this->getCurrentPage($dom))
            ->setTotalPage($this->getTotalPage($dom))
            ->setLawyers($this->getListLawyers($dom));

        return $findResponse;
    }

    protected function getCurrentPage(Dom $dom): int
    {
        if ($span = $dom->find('span.currentStep', 0)) {
            return (int) $span->text();
        }

        return 1;
    }

    protected function getTotalPage(Dom $dom): int
    {
        /**
         * @var HtmlNode[] $collection
         */
        $collection = $dom->find('div.pagination', 0)->find('a.step')->toArray();
        if (0 === count($collection)) {
            return 1;
        }
        $lastStep = (int) end($collection)->text();
        $currentPage = $this->getCurrentPage($dom);

        return $lastStep > $currentPage ? $lastStep : $currentPage;
    }

    /**
     * @param \PHPHtmlParser\Dom $dom
     *
     * @return Lawyer[]
     * @throws ChildNotFoundException
     * @throws NotLoadedException
     */
    protected function getListLawyers(Dom $dom): array
    {
        $data = [];
        /**
         * @var Dom\HtmlNode[]|Collection $nodes
         */
        $nodes = $dom->find('table.persons > tbody > tr');
        foreach ($nodes as $node) {
            /**
             * @var Dom\HtmlNode[]|Collection $tds
             */
            $tds = $node->find('td');
            $tds = array_filter($tds->toArray(), static function (HtmlNode $node) {
                return $node->outerHtml() !== '' && $node->getAttribute('class') !== 'empty';
            });
            $data[] = (new Lawyer())
                ->setRegisterNumber($tds[3]->text())
                ->setFullName($tds[4]->text(true))
                ->setUrl($tds[4]->firstChild()->getAttribute('href'))
                ->setTerritorialSubject($tds[5]->text())
                ->setCertificateNumber($tds[6]->text())
                ->setStatus($tds[7]->text());
        }

        return $data;
    }

    public function detail(string $body): DetailLawyer
    {
        $dom = (new Dom())->loadStr($body);

        return $this->getFullLawyer($dom);
    }

    protected function getFullLawyer(Dom $dom): DetailLawyer
    {
        $nodes = $dom->find('.floating > p.row')->toArray();

        return (new DetailLawyer())
            ->setLawFormation($this->getLawFormation($nodes))
            ->setChamberOfLaw(trim($nodes[5]->text()));
    }

    protected function getLawFormation(array $nodes): ?LawFormation
    {
        $formation = (new LawFormation())
            ->setOrganizationalForm(trim($nodes[7]->text()))
            ->setName(trim($nodes[9]->text()))
            ->setAddress(trim($nodes[11]->text()))
            ->setPhone(trim($nodes[13]->text()))
            ->setEmail(trim($nodes[15]->text()));

        return $formation->getOrganizationalForm() !== '' ? $formation : null;
    }
}
