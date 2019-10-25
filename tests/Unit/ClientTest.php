<?php

namespace SomeWork\Minjust\Tests\Unit;

use DivineOmega\Psr18GuzzleAdapter\Client as GuzzleClient;
use Http\Factory\Guzzle\RequestFactory;
use Http\Factory\Guzzle\StreamFactory;
use Iterator;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use ReflectionClass;
use SomeWork\Minjust\Client;
use SomeWork\Minjust\FindRequest;

/**
 * @coversDefaultClass \SomeWork\Minjust\Client
 */
class ClientTest extends TestCase
{
    /**
     * @covers ::__construct
     * @throws ReflectionException
     */
    public function testConstruct(): void
    {
        $httpClient = $this->createMock(ClientInterface::class);
        $requestFactory = $this->createMock(RequestFactoryInterface::class);
        $streamFactory = $this->createMock(StreamFactoryInterface::class);

        $client = new Client($httpClient, $requestFactory, $streamFactory);

        $ref = new ReflectionClass(Client::class);
        $clientProperty = $ref->getProperty('client');
        $requestFactoryProperty = $ref->getProperty('requestFactory');
        $streamFactoryProperty = $ref->getProperty('streamFactory');

        $clientProperty->setAccessible(true);
        $requestFactoryProperty->setAccessible(true);
        $streamFactoryProperty->setAccessible(true);

        $this->assertEquals($httpClient, $clientProperty->getValue($client));
        $this->assertEquals($requestFactory, $requestFactoryProperty->getValue($client));
        $this->assertEquals($streamFactory, $streamFactoryProperty->getValue($client));
    }

    /**
     * @covers ::list
     * @dataProvider listProvider
     *
     * @param array  $formData
     * @param string $search
     *
     * @throws ClientExceptionInterface
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

    public function listProvider(): Iterator
    {
        yield 'empty' => [
            'formData' => [],
            'search'   => '01/102',
        ];
        yield 'Михайлов' => [
            'formData' => [
                FindRequest::FULL_NAME => 'михайлов олег николаевич',
            ],
            'search'   => '77/2340',
        ];
        yield 'Белоусова Надежда Сергеевна' => [
            'formData' => [
                FindRequest::OFFSET    => 20,
                FindRequest::FULL_NAME => 'б',
                FindRequest::STATUS    => 4,
            ],
            'search'   => '03/2165',
        ];
    }

    /**
     * @covers ::detail
     * @dataProvider detailProvider
     *
     * @param string $url
     * @param string $search
     *
     * @throws ClientExceptionInterface
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

    public function detailProvider(): Iterator
    {
        yield 'Михайлов' => [
            'url'    => '/lawyers/show/1610663',
            'search' => 'Адвокатская палата г. Москвы',
        ];
        yield 'Белоусова Надежда Сергеевна' => [
            'url'    => '/lawyers/show/1625881',
            'search' => 'г. Уфа, ул. К.Маркса, 3б',
        ];
    }
}
