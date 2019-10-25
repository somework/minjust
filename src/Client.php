<?php

namespace SomeWork\Minjust;

use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

/**
 * @see \SomeWork\Minjust\Tests\Unit\ClientTest
 */
class Client
{
    /**
     * @var string
     */
    private const SERVICE_URL = 'http://lawyers.minjust.ru';

    /**
     * @var string
     */
    private const LIST_URL = Client::SERVICE_URL . '/Lawyers';

    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var RequestFactoryInterface
     */
    private $requestFactory;

    /**
     * @var StreamFactoryInterface
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
     * @throws ClientExceptionInterface
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
     * @throws ClientExceptionInterface
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
