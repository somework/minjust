<?php

namespace SomeWork\Minjust\Tests\Unit;

use PHPHtmlParser\Dom;
use PHPUnit\Framework\TestCase;
use SomeWork\Minjust\ListParser;

class PaginationTest extends TestCase
{
    /**
     * @var \SomeWork\Minjust\ListParser
     */
    protected static $strategy;

    public static function setUpBeforeClass(): void
    {
        static::$strategy = new ListParser();
    }

    public static function tearDownAfterClass(): void
    {
        static::$strategy = null;
    }

    /**
     * @dataProvider getTotalPageProvider
     *
     * @param string $resource
     * @param int    $pages
     *
     * @throws \PHPHtmlParser\Exceptions\ChildNotFoundException
     * @throws \PHPHtmlParser\Exceptions\CircularException
     * @throws \PHPHtmlParser\Exceptions\CurlException
     * @throws \PHPHtmlParser\Exceptions\StrictException
     */
    public function testGetTotalPage(string $resource, int $pages): void
    {
        $dom = (new Dom())->load($resource);
        $this->assertEquals($pages, static::$strategy->getTotalPage($dom));
    }

    /**
     * @dataProvider getPageProvider
     *
     * @param string $resource
     * @param int    $page
     *
     * @throws \PHPHtmlParser\Exceptions\ChildNotFoundException
     * @throws \PHPHtmlParser\Exceptions\CircularException
     * @throws \PHPHtmlParser\Exceptions\CurlException
     * @throws \PHPHtmlParser\Exceptions\StrictException
     */
    public function testGetCurrentPage(string $resource, int $page): void
    {
        $dom = (new Dom())->load($resource);
        $this->assertEquals($page, static::$strategy->getCurrentPage($dom), 'Wrong for: ' . $resource);
    }

    /**
     * @dataProvider getListLawyers
     *
     * @param string $resource
     *
     * @param int    $count
     *
     * @throws \PHPHtmlParser\Exceptions\ChildNotFoundException
     * @throws \PHPHtmlParser\Exceptions\CircularException
     * @throws \PHPHtmlParser\Exceptions\CurlException
     * @throws \PHPHtmlParser\Exceptions\StrictException
     */
    public function testGetListLawyers(string $resource, int $count): void
    {
        $dom = (new Dom())->load($resource);
        $lawyers = static::$strategy->getListLawyers($dom);
        $this->assertIsArray($lawyers);
        $this->assertCount($count, $lawyers);
    }

    public function getPageProvider(): array
    {
        return [
            'one-page'            => [
                'resource' => dirname(__DIR__, 2) . '/data/one-page.html',
                'page'     => 1,
            ],
            'many-page'           => [
                'resource' => dirname(__DIR__, 2) . '/data/many-page.html',
                'page'     => 1,
            ],
            'many-page-not-first' => [
                'resource' => dirname(__DIR__, 2) . '/data/many-page-not-first.html',
                'page'     => 6,
            ],
            'rewind-not-first'    => [
                'resource' => dirname(__DIR__, 2) . '/data/rewind-not-first.html',
                'page'     => 2,
            ],
        ];
    }

    public function getTotalPageProvider(): array
    {
        return [
            'one-page'            => [
                'resource' => dirname(__DIR__, 2) . '/data/one-page.html',
                'pages'    => 1,
            ],
            'many-page'           => [
                'resource' => dirname(__DIR__, 2) . '/data/many-page.html',
                'pages'    => 6657,
            ],
            'many-page-not-first' => [
                'resource' => dirname(__DIR__, 2) . '/data/many-page-not-first.html',
                'pages'    => 58,
            ],
            'rewind-not-first'    => [
                'resource' => dirname(__DIR__, 2) . '/data/rewind-not-first.html',
                'pages'    => 2,
            ],
        ];
    }

    public function getListLawyers()
    {
        return [
            'one-page' => [
                'resource' => dirname(__DIR__, 2) . '/data/one-page.html',
                'count'    => 12,
            ],
            //            'many-page'           => [
            //                'resource' => dirname(__DIR__, 2) . '/data/many-page.html',
            //            ],
            //            'many-page-not-first' => [
            //                'resource' => dirname(__DIR__, 2) . '/data/many-page-not-first.html',
            //            ],
            //            'rewind-not-first'    => [
            //                'resource' => dirname(__DIR__, 2) . '/data/rewind-not-first.html',
            //            ],
        ];
    }
}
