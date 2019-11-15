<?php

declare(strict_types=1);

namespace SomeWork\Minjust\Tests\Unit\Parser;

use Iterator;
use PHPHtmlParser\Dom;
use PHPHtmlParser\Exceptions\ChildNotFoundException;
use PHPHtmlParser\Exceptions\CircularException;
use PHPHtmlParser\Exceptions\CurlException;
use PHPHtmlParser\Exceptions\StrictException;
use ReflectionClass;
use ReflectionException;
use SomeWork\Minjust\Entity\Lawyer;
use SomeWork\Minjust\Parser\DomParser;
use SomeWork\Minjust\Parser\ParserInterface;

/**
 * @coversDefaultClass \SomeWork\Minjust\Parser\DomParser
 *
 * @var DomParser $parser
 */
class DomParserTest extends AbstractParserTest
{
    /**
     * @covers ::getTotalPage
     * @dataProvider getTotalPageProvider
     *
     * @param string $resource
     * @param int    $pages
     *
     * @throws ChildNotFoundException
     * @throws CircularException
     * @throws CurlException
     * @throws StrictException
     * @throws ReflectionException
     */
    public function testGetTotalPage(string $resource, int $pages): void
    {
        $dom = (new Dom())->load($resource);
        $parser = $this->getParser();

        $this->assertEquals(
            $pages,
            $this->invokeMethod($parser, 'getTotalPage', [$dom])
        );
    }

    public function getParser(): ParserInterface
    {
        return new DomParser();
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
    public function invokeMethod(&$object, $methodName, array $parameters = [])
    {
        $reflection = new ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    /**
     * @covers ::getCurrentPage
     * @dataProvider getPageProvider
     *
     * @param string $resource
     * @param int    $page
     *
     * @throws ChildNotFoundException
     * @throws CircularException
     * @throws CurlException
     * @throws StrictException
     * @throws ReflectionException
     */
    public function testGetCurrentPage(string $resource, int $page): void
    {
        $dom = (new Dom())->load($resource);
        $parser = $this->getParser();

        $this->assertEquals(
            $page,
            $this->invokeMethod($parser, 'getCurrentPage', [$dom]),
            'Wrong for: ' . $resource
        );
    }

    /**
     * @covers ::getListLawyers
     * @dataProvider getListLawyersProvider
     *
     * @param string $resource
     *
     * @param int    $count
     *
     * @throws ChildNotFoundException
     * @throws CircularException
     * @throws CurlException
     * @throws StrictException
     * @throws ReflectionException
     */
    public function testGetListLawyers(string $resource, int $count): void
    {
        $parser = $this->getParser();

        $dom = (new Dom())->load($resource);
        $lawyers = $this->invokeMethod($parser, 'getListLawyers', [$dom]);
        $this->assertIsArray($lawyers);
        $this->assertCount($count, $lawyers);

        foreach ($lawyers as $lawyer) {
            $this->assertInstanceOf(Lawyer::class, $lawyer);

            $this->assertIsString($lawyer->getFullName());
            $this->assertGreaterThan(0, strlen($lawyer->getFullName()));

            $this->assertIsString($lawyer->getRegisterNumber());
            $this->assertGreaterThan(0, strlen($lawyer->getRegisterNumber()));

            $this->assertIsString($lawyer->getCertificateNumber());
            $this->assertGreaterThanOrEqual(0, strlen($lawyer->getCertificateNumber()));

            $this->assertIsString($lawyer->getStatus());
            $this->assertGreaterThan(0, strlen($lawyer->getStatus()));

            $this->assertIsString($lawyer->getTerritorialSubject());
            $this->assertGreaterThan(0, strlen($lawyer->getTerritorialSubject()));

            $this->assertIsString($lawyer->getUrl());
            $this->assertGreaterThan(0, strlen($lawyer->getUrl()));
        }
    }

    public function getPageProvider(): Iterator
    {
        yield 'one-page' => [
            'resource' => dirname(__DIR__, 2) . '/data/one-page.html',
            'page'     => 1,
        ];
        yield 'many-page' => [
            'resource' => dirname(__DIR__, 2) . '/data/many-page.html',
            'page'     => 1,
        ];
        yield 'many-page-not-first' => [
            'resource' => dirname(__DIR__, 2) . '/data/many-page-not-first.html',
            'page'     => 6,
        ];
        yield 'rewind-not-first' => [
            'resource' => dirname(__DIR__, 2) . '/data/rewind-not-first.html',
            'page'     => 2,
        ];
        yield 'web' => [
            'resource' => 'http://lawyers.minjust.ru/Lawyers',
            'page'     => 1,
        ];
    }

    public function getTotalPageProvider(): Iterator
    {
        yield 'one-page' => [
            'resource' => dirname(__DIR__, 2) . '/data/one-page.html',
            'pages'    => 1,
        ];
        yield 'many-page' => [
            'resource' => dirname(__DIR__, 2) . '/data/many-page.html',
            'pages'    => 6657,
        ];
        yield 'many-page-not-first' => [
            'resource' => dirname(__DIR__, 2) . '/data/many-page-not-first.html',
            'pages'    => 58,
        ];
        yield 'rewind-not-first' => [
            'resource' => dirname(__DIR__, 2) . '/data/rewind-not-first.html',
            'pages'    => 2,
        ];
    }

    public function getListLawyersProvider(): Iterator
    {
        yield 'one-page' => [
            'resource' => dirname(__DIR__, 2) . '/data/one-page.html',
            'count'    => 12,
        ];
        yield 'many-page' => [
            'resource' => dirname(__DIR__, 2) . '/data/many-page.html',
            'count'    => 20,
        ];
        yield 'many-page-not-first' => [
            'resource' => dirname(__DIR__, 2) . '/data/many-page-not-first.html',
            'count'    => 20,
        ];
        yield 'rewind-not-first' => [
            'resource' => dirname(__DIR__, 2) . '/data/rewind-not-first.html',
            'count'    => 8,
        ];
        yield 'web' => [
            'resource' => 'http://lawyers.minjust.ru/Lawyers',
            'count'    => 20,
        ];
    }
}
