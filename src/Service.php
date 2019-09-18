<?php

namespace SomeWork\Minjust;

class Service
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var Parser
     */
    private $parser;

    public function __construct(Client $client, Parser $parser)
    {
        /*
         * @todo вынести в конструктор
         */
        $this->client = $client;
        $this->parser = $parser;
    }

    public function findAll(FindRequest $findRequest): \Generator
    {
        return $this->findFromTo($findRequest, 1, 0);
    }

    public function findFromTo(FindRequest $findRequest, int $startPage = 1, int $endPage = 1): \Generator
    {
        $findRequest->setOffset(($startPage - 1) * $findRequest->getMax());
        $findResponse = $this->find($findRequest);

        yield from $findRequest->isFullData() ? $findResponse->getFullElements() : $findResponse->getElements();
        if ($findResponse->getTotalPage() === 1) {
            return;
        }

        $endPage = $endPage ?: $findResponse->getTotalPage();

        for ($i = $findResponse->getPage(); $i < $endPage; $i++) {
            $endPage = $endPage < $findResponse->getTotalPage() ? $endPage : $findResponse->getTotalPage();
            $findRequest->setOffset($i * $findRequest->getMax());
            yield from $findRequest->isFullData() ?
                $this->find($findRequest)->getFullElements() :
                $this->find($findRequest)->getElements();
        }
    }

    /**
     * @param FindRequest $findRequest
     *
     * @return FindResponse
     * @throws \PHPHtmlParser\Exceptions\ChildNotFoundException
     * @throws \PHPHtmlParser\Exceptions\CircularException
     * @throws \PHPHtmlParser\Exceptions\CurlException
     * @throws \PHPHtmlParser\Exceptions\NotLoadedException
     * @throws \PHPHtmlParser\Exceptions\StrictException
     */
    public function find(FindRequest $findRequest): FindResponse
    {
        $response = $this->parser->buildListResponse(
            $this->client->list($findRequest->getFormData())
        );

        $response->setFullElements(
            $this->loadDetails(
                $response->getElements()
            )
        );

        return $response;
    }

    /**
     * @param \SomeWork\Minjust\Entity\Lawyer[] $lawyers
     *
     * @return \Generator
     */
    protected function loadDetails(array $lawyers): \Generator
    {
        foreach ($lawyers as $lawyer) {
            yield $this->parser->buildFullLawyer(
                $lawyer,
                $this->client->detail($lawyer->getUrl())
            );
        }
    }
}
