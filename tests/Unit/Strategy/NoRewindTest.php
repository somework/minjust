<?php

namespace SomeWork\Minjust\Tests\Unit\Strategy;

use PHPHtmlParser\Dom;
use PHPUnit\Framework\TestCase;
use SomeWork\Minjust\PaginationStrategy\NoRewind;

class NoRewindTest extends TestCase
{
    /**
     * @var \SomeWork\Minjust\PaginationStrategy\NoRewind
     */
    protected static $strategy;

    /**
     * @var \PHPHtmlParser\Dom
     */
    protected $dom;

    public static function setUpBeforeClass(): void
    {
        static::$strategy = new NoRewind();
    }

    public static function tearDownAfterClass(): void
    {
        static::$strategy = null;
    }

    public function setUp(): void
    {
        $this->dom = new Dom();
    }

    public function tearDown(): void
    {
        unset($this->dom);
    }

    /**
     * @dataProvider getPageDataProvider
     *
     * @param string $resource
     * @param int    $page
     *
     * @throws \PHPHtmlParser\Exceptions\ChildNotFoundException
     * @throws \PHPHtmlParser\Exceptions\CircularException
     * @throws \PHPHtmlParser\Exceptions\CurlException
     * @throws \PHPHtmlParser\Exceptions\StrictException
     */
    public function testGetPage(string $resource, int $page): void
    {
        /* @noinspection PhpUnhandledExceptionInspection */
        $this->dom->load($resource);
        $this->assertEquals($page, static::$strategy->getPage($this->dom));
    }

    /**
     * @dataProvider isSupportDataProvider
     *
     * @param string $resource
     * @param bool   $pagination
     *
     * @throws \PHPHtmlParser\Exceptions\ChildNotFoundException
     * @throws \PHPHtmlParser\Exceptions\CircularException
     * @throws \PHPHtmlParser\Exceptions\CurlException
     * @throws \PHPHtmlParser\Exceptions\StrictException
     */
    public function testIsSupport(string $resource, bool $pagination): void
    {
        /* @noinspection PhpUnhandledExceptionInspection */
        $this->dom->load($resource);
        $this->assertEquals($pagination, static::$strategy->isSupport($this->dom));
    }

    /**
     * @dataProvider getTotalPageDataProvider
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
        $this->dom->load($resource);
        $this->assertEquals($pages, static::$strategy->getTotalPage($this->dom));
    }

    public function getPageDataProvider(): array
    {
        return [
            [
                'resource' => dirname(__DIR__, 2) . '/data/many-page.html',
                'page'     => 1,
            ],
            [
                'resource' => dirname(__DIR__, 2) . '/data/many-page-not-first.html',
                'page'     => 6,
            ],
        ];
    }

    public function getTotalPageDataProvider(): array
    {
        return [
            [
                'resource' => dirname(__DIR__, 2) . '/data/many-page.html',
                'pages'    => 6657,
            ],
            [
                'resource' => dirname(__DIR__, 2) . '/data/many-page-not-first.html',
                'pages'    => 58,
            ],
        ];
    }

    public function isSupportDataProvider(): array
    {
        return [
            'one-page'  => [
                'resource'   => dirname(__DIR__, 2) . '/data/one-page.html',
                'pagination' => false,
            ],
            'many-page' => [
                'resource'   => dirname(__DIR__, 2) . '/data/many-page.html',
                'pagination' => true,
            ],
            [
                'resource'   => dirname(__DIR__, 2) . '/data/rewind-not-first.html',
                'pagination' => false,
            ],
        ];
    }
}
