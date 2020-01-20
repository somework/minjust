<?php

declare(strict_types=1);

namespace SomeWork\Minjust;

use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use SomeWork\Minjust\Exception\HttpClientException;
use SomeWork\Minjust\Exception\WrongStatusCodeException;

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

    public function __construct(
        ClientInterface $client,
        RequestFactoryInterface $requestFactory
    ) {
        $this->client = $client;
        $this->requestFactory = $requestFactory;
    }

    /**
     * @param array $formData
     *
     * @return string
     * @throws WrongStatusCodeException
     * @throws HttpClientException
     */
    public function list(array $formData = []): string
    {
        $query = '';
        if ([] !== $formData) {
            $query .= '?' . http_build_query($formData);
        }

        $request = $this
                ->requestFactory
                ->createRequest('GET', static::LIST_URL . $query);

        return $this->handleRequest($request);
    }

    /**
     * @param string $url
     *
     * @return string
     * @throws WrongStatusCodeException
     * @throws HttpClientException
     */
    public function detail(string $url): string
    {
        $request = $this
            ->requestFactory
            ->createRequest('GET', static::SERVICE_URL . $url);

        return $this->handleRequest($request);
    }

    /**
     * @param RequestInterface $request
     *
     * @return string
     * @throws WrongStatusCodeException
     * @throws HttpClientException
     */
    public function handleRequest(RequestInterface $request):string
    {
        try {
            $response = $this->client->sendRequest($request);
        } catch (ClientExceptionInterface $e) {
            throw new HttpClientException($e->getMessage(), $e->getCode(), $e);
        }
        if ($response->getStatusCode() === 200) {
            return $response->getBody()->getContents();
        }

        throw new WrongStatusCodeException($response->getStatusCode());
    }
}
