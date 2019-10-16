<?php

namespace SomeWork\Minjust;

use PHPHtmlParser\Dom;
use SomeWork\Minjust\Entity\DetailLawyer;
use SomeWork\Minjust\Entity\LawFormation;
use SomeWork\Minjust\Entity\Lawyer;

class DomParser implements ParserInterface
{
    public function getTotalPage(Dom $dom): int
    {
        /**
         * @var \PHPHtmlParser\Dom\HtmlNode[] $collection
         */
        $collection = $dom->find('div.pagination', 0)->find('a.step')->toArray();
        if (0 === count($collection)) {
            return 1;
        }
        $lastStep = (int) end($collection)->text();
        $currentPage = $this->getCurrentPage($dom);

        return $lastStep > $currentPage ? $lastStep : $currentPage;
    }

    public function getCurrentPage(Dom $dom): int
    {
        if ($span = $dom->find('span.currentStep', 0)) {
            return (int) $span->text();
        }

        return 1;
    }

    /**
     * @param \PHPHtmlParser\Dom $dom
     *
     * @return \SomeWork\Minjust\Entity\Lawyer[]
     * @throws \PHPHtmlParser\Exceptions\ChildNotFoundException
     * @throws \PHPHtmlParser\Exceptions\NotLoadedException
     */
    public function getListLawyers(Dom $dom): array
    {
        $data = [];
        /**
         * @var Dom\HtmlNode[]|\PHPHtmlParser\Dom\Collection $nodes
         */
        $nodes = $dom->find('table.persons > tbody > tr');
        foreach ($nodes as $node) {
            /**
             * @var Dom\HtmlNode[]|\PHPHtmlParser\Dom\Collection $tds
             */
            $tds = $node->find('td');
            $tds = array_filter($tds->toArray(), static function (Dom\HtmlNode $node) {
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

    public function getFullLawyer(Dom $dom): DetailLawyer
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

        return $formation->getOrganizationalForm() ? $formation : null;
    }
}
