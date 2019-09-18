<?php

namespace SomeWork\Minjust;

use SomeWork\Minjust\Entity\Lawyer;
use SomeWork\Minjust\Strategy\ParseStrategyInterface;
use PHPHtmlParser\Dom;

class Parser
{
    /**
     * @var ParseStrategyInterface[]
     */
    private $strategies;

    public function __construct(array $strategies)
    {
        $this->strategies = $strategies;
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
    public function buildResponse(string $body): FindResponse
    {
        $dom = new Dom();
        $dom->load($body);
        $strategy = $this->guessStrategy($dom);

        $findResponse = new FindResponse();
        $findResponse
            ->setPage($strategy->getPage($dom))
            ->setTotalPage($strategy->getTotalPage($dom))
            ->setElements($this->getElements($dom));

        return $findResponse;
    }

    protected function guessStrategy(Dom $dom)
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->isSupport($dom)) {
                return $strategy;
            }
        }

        throw new \LogicException('No strategy found for current dom');
    }

    /**
     * @param Dom $dom
     *
     * @return Lawyer[]
     * @throws \PHPHtmlParser\Exceptions\ChildNotFoundException
     * @throws \PHPHtmlParser\Exceptions\NotLoadedException
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
