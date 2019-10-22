<?php

namespace SomeWork\Minjust;

use Generator;
use Psr\Http\Client\ClientExceptionInterface;
use SomeWork\Minjust\Entity\Lawyer;
use SomeWork\Minjust\Parser\ParserInterface;

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
     * @throws ClientExceptionInterface
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
     * @throws ClientExceptionInterface
     * @todo Упростить логику метода
     */
    public function findFromTo(FindRequest $findRequest, int $startPage = 1, int $endPage = 1): Generator
    {
        $findRequest->setOffset(($startPage - 1) * $findRequest->getMax());
        $findResponse = $this->find($findRequest);

        yield from $findRequest->isFullData() ? $findResponse->getDetailLawyers() : $findResponse->getLawyers();
        if ($findResponse->getTotalPage() === 1) {
            return;
        }

        $endPage = $endPage ?: $findResponse->getTotalPage();

        for ($i = $findResponse->getPage(); $i < $endPage; $i++) {
            $endPage = $endPage < $findResponse->getTotalPage() ? $endPage : $findResponse->getTotalPage();
            $findRequest->setOffset($i * $findRequest->getMax());
            yield from $findRequest->isFullData() ?
                $this->find($findRequest)->getDetailLawyers() :
                $this->find($findRequest)->getLawyers();
        }
    }

    /**
     * @param FindRequest $findRequest
     *
     * @return FindResponse
     * @throws ClientExceptionInterface
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
     * @throws ClientExceptionInterface
     */
    protected function getDetailLawyersGenerator(array $lawyers): Generator
    {
        foreach ($lawyers as $lawyer) {
            yield $this->parser->detail(
                $this->client->detail($lawyer->getUrl())
            );
        }
    }
}
