<?php

namespace BreviManu\Minjust\Strategy;

use PHPHtmlParser\Dom;

abstract class AbstractParseStrategy implements ParseStrategyInterface
{
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
            $data[] = [
                'Реестровый номер'    => $tds[3]->text(),
                'ФИО адвоката'        => $tds[4]->text(true),
                'Субъект РФ'          => $tds[5]->text(),
                'Номер удостоверения' => $tds[6]->text(),
                'Текущий статус'      => $tds[7]->text(),
            ];
        }

        return $data;
    }
}
