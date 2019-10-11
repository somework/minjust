<?php

namespace SomeWork\Minjust;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;

class Client
{
    private const SERVICE_URL = 'http://lawyers.minjust.ru';

    private const LIST_URL = Client::SERVICE_URL . '/Lawyers';

    /**
     * @var \Psr\Http\Client\ClientInterface
     */
    private $client;

    /**
     * @var \Psr\Http\Message\RequestFactoryInterface
     */
    private $requestFactory;

    public function __construct(ClientInterface $client, RequestFactoryInterface $requestFactory)
    {
        $this->client = $client;
        $this->requestFactory = $requestFactory;
    }

    /**
     * @param array $formData
     *
     * @return string
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function list(array $formData = []): string
    {
        $request = $this
            ->requestFactory
            ->createRequest('GET', static::LIST_URL . '?' . http_build_query($formData));

        return $this
            ->client
            ->sendRequest($request)
            ->getBody()
            ->getContents();
    }

    /**
     * @param string $url
     *
     * @return string
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function detail(string $url): string
    {
        $request = $this
            ->requestFactory
            ->createRequest('GET', static::SERVICE_URL . $url);

        return $this
            ->client
            ->sendRequest($request)
            ->getBody()
            ->getContents();
    }
}
