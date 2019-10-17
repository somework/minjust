<?php

namespace SomeWork\Minjust\Tests\Unit;

use DivineOmega\Psr18GuzzleAdapter\Client as GuzzleClient;
use Http\Factory\Guzzle\RequestFactory;
use Http\Factory\Guzzle\StreamFactory;
use PHPUnit\Framework\TestCase;
use SomeWork\Minjust\Client;
use SomeWork\Minjust\FindRequest;

class ClientTest extends TestCase
{
    /**
     * @dataProvider listProvider
     *
     * @param array  $formData
     * @param string $search
     *
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function testList(array $formData, string $search): void
    {
        $client = new Client(
            new GuzzleClient(),
            new RequestFactory(),
            new StreamFactory()
        );

        $body = $client->list($formData);
        $this->assertStringContainsString($search, $body);
    }

    public function listProvider(): array
    {
        return [
            'empty'                       => [
                'formData' => [],
                'search'   => '01/102',
            ],
            'Михайлов'                    => [
                'formData' => [
                    FindRequest::FULL_NAME => 'михайлов олег николаевич',
                ],
                'search'   => '77/2340',
            ],
            'Белоусова Надежда Сергеевна' => [
                'formData' => [
                    FindRequest::OFFSET    => 20,
                    FindRequest::FULL_NAME => 'б',
                    FindRequest::STATUS    => 4,
                ],
                'search'   => '03/2165',
            ],
        ];
    }

    /**
     * @dataProvider detailProvider
     *
     * @param string $url
     * @param string $search
     *
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function testDetail(string $url, string $search): void
    {
        $client = new Client(
            new GuzzleClient(),
            new RequestFactory(),
            new StreamFactory()
        );

        $body = $client->detail($url);
        $this->assertStringContainsString($search, $body);
    }

    public function detailProvider(): array
    {
        return [
            'Михайлов'                    => [
                'url'    => '/lawyers/show/1610663',
                'search' => 'Адвокатская палата г. Москвы',
            ],
            'Белоусова Надежда Сергеевна' => [
                'url'    => '/lawyers/show/1625881',
                'search' => 'г. Уфа, ул. К.Маркса, 3б',
            ],
        ];
    }
}
