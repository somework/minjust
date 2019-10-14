<?php

namespace SomeWork\Minjust\Tests\Unit\Strategy;

use PHPHtmlParser\Dom;
use PHPUnit\Framework\TestCase;
use SomeWork\Minjust\PaginationStrategy\NoPagination;

class NoPaginationTest extends TestCase
{
    /**
     * @var \SomeWork\Minjust\PaginationStrategy\NoPagination
     */
    protected static $strategy;

    /**
     * @var \PHPHtmlParser\Dom
     */
    protected $dom;

    public static function setUpBeforeClass(): void
    {
        static::$strategy = new NoPagination();
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
     * @dataProvider domProvider
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
        $this->dom->load($resource);
        $this->assertEquals(!$pagination, static::$strategy->isSupport($this->dom));
    }

    public function domProvider(): array
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
        ];
    }

    public function testGetPage(): void
    {
        $this->assertEquals(1, static::$strategy->getPage($this->dom));
    }

    public function testGetTotalPage(): void
    {
        $this->assertEquals(1, static::$strategy->getTotalPage($this->dom));
    }
}
