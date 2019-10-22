<?php

namespace SomeWork\Minjust\Tests\Unit;

use Iterator;
use PHPUnit\Framework\TestCase;
use SomeWork\Minjust\Entity\LawFormation;
use SomeWork\Minjust\Entity\Lawyer;
use SomeWork\Minjust\Parser\ParserInterface;

abstract class AbstractParserTest extends TestCase
{
    /**
     * @var \SomeWork\Minjust\Parser\ParserInterface
     */
    protected static $parser;

    public static function setUpBeforeClass(): void
    {
        static::$parser = static::getNewParser();
    }

    abstract public static function getNewParser(): ParserInterface;

    public static function tearDownAfterClass(): void
    {
        static::$parser = null;
    }

    /**
     * @dataProvider listProvider
     *
     * @param string $resource
     * @param int    $page
     * @param int    $totalPages
     * @param int    $count
     */
    public function testList(string $resource, int $page, int $totalPages, int $count): void
    {
        $body = file_get_contents($resource);
        $response = static::$parser->list($body);

        $this->assertEquals($page, $response->getPage());
        $this->assertEquals($totalPages, $response->getTotalPage());
        $this->assertCount($count, $response->getLawyers());
        $this->assertContainsOnlyInstancesOf(Lawyer::class, $response->getLawyers());
    }

    /**
     * @dataProvider detailProvider
     *
     * @param string            $resource
     * @param string            $chamberOfLaw
     * @param LawFormation|null $lawFormation
     */
    public function testDetail(string $resource, string $chamberOfLaw, ?LawFormation $lawFormation): void
    {
        $body = file_get_contents($resource);
        $lawyer = static::$parser->detail($body);

        $this->assertIsString($lawyer->getChamberOfLaw());
        $this->assertEquals($chamberOfLaw, $lawyer->getChamberOfLaw());

        if (null === $lawFormation) {
            $this->assertNull($lawyer->getLawFormation());
        } else {
            $this->assertIsString($lawyer->getLawFormation()->getPhone());
            $this->assertIsString($lawyer->getLawFormation()->getEmail());
            $this->assertIsString($lawyer->getLawFormation()->getName());
            $this->assertIsString($lawyer->getLawFormation()->getOrganizationalForm());
            $this->assertIsString($lawyer->getLawFormation()->getAddress());

            $this->assertEquals($lawFormation->getPhone(), $lawyer->getLawFormation()->getPhone());
            $this->assertEquals($lawFormation->getEmail(), $lawyer->getLawFormation()->getEmail());
            $this->assertEquals($lawFormation->getName(), $lawyer->getLawFormation()->getName());
            $this->assertEquals(
                $lawFormation->getOrganizationalForm(),
                $lawyer->getLawFormation()->getOrganizationalForm()
            );
            $this->assertEquals($lawFormation->getAddress(), $lawyer->getLawFormation()->getAddress());
        }
    }

    public function listProvider(): Iterator
    {
        yield [
            dirname(__DIR__) . '/data/one-page.html',
            'page'       => 1,
            'totalPages' => 1,
            'count'      => 12,
        ];
        yield [
            dirname(__DIR__) . '/data/rewind-not-first.html',
            'page'       => 2,
            'totalPages' => 2,
            'count'      => 8,
        ];
        yield [
            dirname(__DIR__) . '/data/many-page-not-first.html',
            'page'       => 6,
            'totalPages' => 58,
            'count'      => 20,
        ];
        yield [
            dirname(__DIR__) . '/data/many-page.html',
            'page'       => 1,
            'totalPages' => 6657,
            'count'      => 20,
        ];
        yield 'web' => [
            'http://lawyers.minjust.ru/Lawyers?regnumber=77/2340',
            'page'       => 1,
            'totalPages' => 1,
            'count'      => 1,
        ];
    }

    public function detailProvider(): Iterator
    {
        yield 'web Михайлов' => [
            'http://lawyers.minjust.ru/lawyers/show/1610663',
            'Адвокатская палата г. Москвы',
            null,
        ];
        yield 'web Трофимова' => [
            'http://lawyers.minjust.ru/lawyers/show/1529728',
            'Палата адвокатов Республики Алтай',
            (new LawFormation())
                ->setOrganizationalForm('Коллегии адвокатов')
                ->setName('Коллегия адвокатов Республики Алтай')
                ->setAddress('г.Бийск, ул.Л.Толстого, 160, кв.12')
                ->setPhone('89609626799'),
        ];
    }
}
