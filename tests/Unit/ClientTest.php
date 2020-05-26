<?php

declare(strict_types=1);

namespace SomeWork\Minjust\Tests\Unit;

use DivineOmega\Psr18GuzzleAdapter\Client as GuzzleClient;
use DivineOmega\Psr18GuzzleAdapter\Exceptions\ClientException;
use Http\Factory\Guzzle\RequestFactory;
use Iterator;
use PHPUnit\Framework\MockObject\Rule\InvokedCount as InvokedCountMatcher;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use ReflectionClass;
use ReflectionException;
use SomeWork\Minjust\Client;
use SomeWork\Minjust\Exception\HttpClientException;
use SomeWork\Minjust\Exception\WrongStatusCodeException;
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

        $client = new Client($httpClient, $requestFactory);

        $ref = new ReflectionClass(Client::class);
        $clientProperty = $ref->getProperty('client');
        $requestFactoryProperty = $ref->getProperty('requestFactory');

        $clientProperty->setAccessible(true);
        $requestFactoryProperty->setAccessible(true);

        $this->assertEquals($httpClient, $clientProperty->getValue($client));
        $this->assertEquals($requestFactory, $requestFactoryProperty->getValue($client));
    }

    /**
     * @covers ::list
     * @dataProvider listProvider
     *
     * @param array  $formData
     * @param string $search
     */
    public function testList(array $formData, string $search): void
    {
        $client = new Client(
            new GuzzleClient(),
            new RequestFactory()
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
                FindRequest::FULL_NAME           => 'михайлов олег николаевич',
                FindRequest::TERRITORIAL_SUBJECT => '77',
            ],
            'search'   => '77/2340',
        ];
        yield 'Белоусова Надежда Сергеевна' => [
            'formData' => [
                FindRequest::PAGE      => 1,
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
     */
    public function testDetail(string $url, string $search): void
    {
        $client = new Client(
            new GuzzleClient(),
            new RequestFactory()
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

    /**
     * @covers ::handleRequest
     */
    public function testHttpClientExceptionHandleRequest(): void
    {
        $request = $this->createMock(RequestInterface::class);

        $exception = new ClientException('Test Message', 123);

        $httpClient = $this->createMock(ClientInterface::class);
        $requestFactory = $this->createMock(RequestFactoryInterface::class);

        $httpClient
            ->expects($this->once())
            ->method('sendRequest')
            ->with($request)
            ->willThrowException($exception);

        $this->expectException(HttpClientException::class);
        $this->expectExceptionCode(123);
        $this->expectExceptionMessage('Test Message');

        $client = new Client($httpClient, $requestFactory);
        /* @noinspection PhpUnhandledExceptionInspection */
        $this->invokeMethod($client, 'handleRequest', [$request]);
    }

    /**
     * Call protected/private method of a class.
     *
     * @param object &$object     Instantiated object that we will run method on.
     * @param string  $methodName Method name to call
     * @param array   $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     * @throws ReflectionException
     */
    public function invokeMethod($object, $methodName, array $parameters = [])
    {
        $reflection = new ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    /**
     * @throws ReflectionException
     */
    public function testWrongStatusCodeException(): void
    {
        $httpClient = $this->createMock(ClientInterface::class);
        $requestFactory = $this->createMock(RequestFactoryInterface::class);

        $request = $this->createMock(RequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $response
            ->expects(new InvokedCountMatcher(2))
            ->method('getStatusCode')
            ->willReturn(500);

        $httpClient
            ->expects($this->once())
            ->method('sendRequest')
            ->willReturn($response);

        $this->expectException(WrongStatusCodeException::class);

        $client = new Client($httpClient, $requestFactory);
        $this->invokeMethod($client, 'handleRequest', [$request]);
    }
}
