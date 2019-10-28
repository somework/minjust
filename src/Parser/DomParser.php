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

/**
 * @see \SomeWork\Minjust\Tests\Unit\DomParserTest
 */
class DomParser implements ParserInterface
{
    /**
     * @var string
     */
    protected const CURRENT_PAGE_SELECTOR = 'span.currentStep';

    /**
     * @var string
     */
    protected const PAGINATION_BLOCK_SELECTOR = 'div.pagination';

    /**
     * @var string
     */
    protected const PAGINATION_STEP_SELECTOR = 'a.step';

    /**
     * @var string
     */
    protected const LAWYERS_LIST_BLOCK_SELECTOR = 'table.persons > tbody > tr';

    /**
     * @var string
     */
    protected const LAWYER_DETAIL_SELECTOR = '.floating > p.row';

    public function list(string $body): FindResponse
    {
        $dom = (new Dom())->loadStr($body);

        return (new FindResponse())
            ->setPage($this->getCurrentPage($dom))
            ->setTotalPage($this->getTotalPage($dom))
            ->setLawyers($this->getListLawyers($dom));
    }

    protected function getCurrentPage(Dom $dom): int
    {
        if ($span = $dom->find(static::CURRENT_PAGE_SELECTOR, 0)) {
            return (int) $span->text();
        }

        return 1;
    }

    protected function getTotalPage(Dom $dom): int
    {
        /**
         * @var HtmlNode[] $collection
         */
        $collection = $dom
            ->find(static::PAGINATION_BLOCK_SELECTOR, 0)
            ->find(static::PAGINATION_STEP_SELECTOR)
            ->toArray();
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
        $nodes = $dom->find(static::LAWYERS_LIST_BLOCK_SELECTOR);
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

        /**
         * @var Dom\HtmlNode[] $nodes
         */
        $nodes = $dom->find(static::LAWYER_DETAIL_SELECTOR)->toArray();

        $lawyer = (new DetailLawyer())
            ->setChamberOfLaw(trim($nodes[5]->text()));
        if (($organizationForm = trim($nodes[7]->text())) !== '') {
            $lawyer->setLawFormation(
                (new LawFormation())
                    ->setOrganizationalForm($organizationForm)
                    ->setName(trim($nodes[9]->text()))
                    ->setAddress(trim($nodes[11]->text()))
                    ->setPhone(trim($nodes[13]->text()))
                    ->setEmail(trim($nodes[15]->text()))
            );
        }

        return $lawyer;
    }
}
