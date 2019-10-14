<?php

namespace SomeWork\Minjust\Tests\Unit\Strategy;

use PHPHtmlParser\Dom;
use PHPUnit\Framework\TestCase;
use SomeWork\Minjust\PaginationStrategy\Rewind;

class RewindTest extends TestCase
{
    /**
     * @var \SomeWork\Minjust\PaginationStrategy\Rewind
     */
    protected static $strategy;

    /**
     * @var \PHPHtmlParser\Dom
     */
    protected $dom;

    public static function setUpBeforeClass(): void
    {
        static::$strategy = new Rewind();
    }

    public static function tearDownAfterClass(): void
    {
        static::$strategy = null;
    }

    public function setUp(): void
    {
        $this->dom = new Dom();
        $this->dom->removeSelfClosingTag('br');
        $this->dom->removeSelfClosingTag('link');
        $this->dom->removeSelfClosingTag('meta');
        $this->dom->addNoSlashTag('br');
        $this->dom->addNoSlashTag('link');
        $this->dom->addNoSlashTag('meta');
        $this->dom->addNoSlashTag('input');
    }

    public function tearDown(): void
    {
        unset($this->dom);
    }

    /**
     * @dataProvider isSupportDataProvider
     *
     * @param string $resource
     *
     * @throws \PHPHtmlParser\Exceptions\ChildNotFoundException
     * @throws \PHPHtmlParser\Exceptions\CircularException
     * @throws \PHPHtmlParser\Exceptions\CurlException
     * @throws \PHPHtmlParser\Exceptions\StrictException
     */
    public function testIsSupport(string $resource): void
    {
        /* @noinspection PhpUnhandledExceptionInspection */
        $this->dom->load($resource);
        $this->assertEquals(true, static::$strategy->isSupport($this->dom));
    }

    public function isSupportDataProvider()
    {
        return [
            [
                'resource' => 'http://lawyers.minjust.ru/Lawyers?lawyername=&regnumber=&lawicard=&lawstatus=4&formation=4&lawregion=&max=20&offset=20',
            ],
        ];
    }
}
