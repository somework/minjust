<?php

namespace SomeWork\Minjust;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

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

    /**
     * @var \Psr\Http\Message\StreamFactoryInterface
     */
    private $streamFactory;

    public function __construct(
        ClientInterface $client,
        RequestFactoryInterface $requestFactory,
        StreamFactoryInterface $streamFactory
    ) {
        $this->client = $client;
        $this->requestFactory = $requestFactory;
        $this->streamFactory = $streamFactory;
    }

    /**
     * @param array $formData
     *
     * @return string
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function list(array $formData = []): string
    {
        if ([] === $formData) {
            $request = $this
                ->requestFactory
                ->createRequest('GET', static::LIST_URL);
        } else {
            $request = $this
                ->requestFactory
                ->createRequest('POST', static::LIST_URL)
                ->withBody($this->streamFactory->createStream(http_build_query($formData, null, '&')))
                ->withHeader('Content-Type', 'application/x-www-form-urlencoded');
        }

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
