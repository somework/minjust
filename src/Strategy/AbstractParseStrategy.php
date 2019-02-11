<?php

namespace BreviManu\Minjust\Strategy;

use BreviManu\Minjust\Entity\Lawyer;
use PHPHtmlParser\Dom;

abstract class AbstractParseStrategy implements ParseStrategyInterface
{
    /**
     * @param Dom $dom
     *
     * @return Lawyer[]
     */
    public function getElements(Dom $dom): array
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
            $tds = array_filter($tds->toArray(), function (Dom\HtmlNode $node) {
                return $node->outerHtml() !== '' && $node->getAttribute('class') !== 'empty';
            });
            $data[] = (new Lawyer())
                ->setFullName($tds[4]->text(true))
                ->setRegisterNumber($tds[3]->text())
                ->setCertificateNumber($tds[6]->text())
                ->setStatus($tds[7]->text())
                ->setTerritorialSubject($tds[5]->text());
        }

        return $data;
    }
}
