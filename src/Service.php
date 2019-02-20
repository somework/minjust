<?php

namespace SomeWork\Minjust;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class Service
{
    private const SERVICE_URL = 'http://lawyers.minjust.ru/Lawyers';

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

        yield from $findResponse->getElements();
        if ($findResponse->getTotalPage() === 1) {
            return;
        }

        for ($i = $findResponse->getPage(); $i < $endPage ?: $findResponse->getTotalPage(); $i++) {
            $findRequest->setOffset($i * $findRequest->getMax());
            yield from $this->find($findRequest)->getElements();
        }
    }

    /**
     * @param FindRequest $findRequest
     *
     * @return FindResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function find(FindRequest $findRequest): FindResponse
    {
        return $this->parser->buildResponse(
            $this
                ->client
                ->request('GET', static::SERVICE_URL, [
                    RequestOptions::QUERY => $findRequest->getFormData(),
                ])
                ->getBody()
        );
    }
}
