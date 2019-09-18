<?php

namespace SomeWork\Minjust;

use GuzzleHttp\RequestOptions;

class Client
{
    private const SERVICE_URL = 'http://lawyers.minjust.ru';

    /**
     * @var \GuzzleHttp\Client
     */
    private $client;

    public function __construct(\GuzzleHttp\Client $client)
    {
        $this->client = $client;
    }

    public function list(array $formData = []): string
    {
        return $this
            ->client
            ->request('GET', static::SERVICE_URL . '/Lawyers', [
                RequestOptions::QUERY => $formData,
            ])
            ->getBody()
            ->getContents();
    }

    public function detail(string $url)
    {
        return $this
            ->client
            ->request('GET', static::SERVICE_URL . $url)
            ->getBody()
            ->getContents();
    }
}
