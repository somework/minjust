<?php

namespace SomeWork\Minjust;

use PHPHtmlParser\Dom;
use SomeWork\Minjust\Entity\Lawyer;

class ListParser implements ListParserInterface
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
}
