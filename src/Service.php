<?php

declare(strict_types=1);

namespace SomeWork\Minjust;

use Generator;
use SomeWork\Minjust\Entity\Lawyer;
use SomeWork\Minjust\Parser\ParserInterface;

/**
 * @see \SomeWork\Minjust\Tests\Unit\ServiceTest
 */
class Service
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var ParserInterface
     */
    private $parser;

    public function __construct(Client $client, ParserInterface $parser)
    {
        $this->client = $client;
        $this->parser = $parser;
    }

    /**
     * @param FindRequest $findRequest
     *
     * @return Generator
     */
    public function findAll(FindRequest $findRequest): Generator
    {
        return $this->findFromTo($findRequest, 1, 0);
    }

    /**
     * @param FindRequest $findRequest
     * @param int         $startPage
     * @param int         $endPage
     *
     * @return Generator
     * @todo Упростить логику метода
     */
    public function findFromTo(FindRequest $findRequest, int $startPage = 1, int $endPage = 1): Generator
    {
        $findRequest->setPage($startPage);
        $findResponse = $this->find($findRequest);

        yield from $findRequest->isFullData() ? $findResponse->getDetailLawyers() : $findResponse->getLawyers();
        if ($findResponse->getTotalPage() === 1) {
            return;
        }

        $endPage = $endPage ?: $findResponse->getTotalPage();

        for ($i = $findResponse->getPage(); $i < $endPage; $i++) {
            $endPage = $endPage < $findResponse->getTotalPage() ? $endPage : $findResponse->getTotalPage();
            $findRequest->setPage($i);
            yield from $findRequest->isFullData() ?
                $this->find($findRequest)->getDetailLawyers() :
                $this->find($findRequest)->getLawyers();
        }
    }

    /**
     * @param FindRequest $findRequest
     *
     * @return FindResponse
     */
    public function find(FindRequest $findRequest): FindResponse
    {
        $findResponse = $this->parser->list(
            $this->client->list($findRequest->getFormData())
        );

        $findResponse->setDetailLawyersGenerator(
            $this->getDetailLawyersGenerator($findResponse->getLawyers())
        );

        return $findResponse;
    }

    /**
     * @param Lawyer[] $lawyers
     *
     * @return Generator
     */
    protected function getDetailLawyersGenerator(array $lawyers): Generator
    {
        foreach ($lawyers as $lawyer) {
            yield $this
                ->parser
                ->lawyer($this->client->detail($lawyer->getUrl()))
                ->loadFromLawyer($lawyer);
        }
    }
}
