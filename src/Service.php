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
        $findRequest->setOffset(0);
        $findResponse = $this->find($findRequest);

        yield from $findResponse->getElements();
        if ($findResponse->getTotalPage() === 1) {
            return;
        }

        for ($i = $findResponse->getPage(); $i < $findResponse->getTotalPage(); $i++) {
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
