<?php

namespace SomeWork\Minjust\Tests\Unit;

use Iterator;
use PHPHtmlParser\Dom;
use ReflectionClass;
use SomeWork\Minjust\Entity\DetailLawyer;
use SomeWork\Minjust\Entity\LawFormation;
use SomeWork\Minjust\Entity\Lawyer;
use SomeWork\Minjust\Parser\DomParser;
use SomeWork\Minjust\Parser\ParserInterface;

/**
 * @var DomParser $parser
 */
class DomParserTest extends AbstractParserTest
{
    public static function getNewParser(): ParserInterface
    {
        return new DomParser();
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
     * @throws \ReflectionException
     */
    public function testGetTotalPage(string $resource, int $pages): void
    {
        $dom = (new Dom())->load($resource);

        $this->assertEquals(
            $pages,
            $this->invokeMethod(static::$parser, 'getTotalPage', [$dom])
        );
    }

    /**
     * Call protected/private method of a class.
     *
     * @param object &$object     Instantiated object that we will run method on.
     * @param string  $methodName Method name to call
     * @param array   $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     * @throws \ReflectionException
     */
    public function invokeMethod(&$object, $methodName, array $parameters = [])
    {
        $reflection = new ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
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
     * @throws \ReflectionException
     */
    public function testGetCurrentPage(string $resource, int $page): void
    {
        $dom = (new Dom())->load($resource);
        $this->assertEquals($page, $this->invokeMethod(static::$parser, 'getCurrentPage', [$dom]),
            'Wrong for: ' . $resource);
    }

    /**
     * @dataProvider getListLawyersProvider
     *
     * @param string $resource
     *
     * @param int    $count
     *
     * @throws \PHPHtmlParser\Exceptions\ChildNotFoundException
     * @throws \PHPHtmlParser\Exceptions\CircularException
     * @throws \PHPHtmlParser\Exceptions\CurlException
     * @throws \PHPHtmlParser\Exceptions\StrictException
     * @throws \ReflectionException
     */
    public function testGetListLawyers(string $resource, int $count): void
    {
        $dom = (new Dom())->load($resource);
        $lawyers = $this->invokeMethod(static::$parser, 'getListLawyers', [$dom]);
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
            'resource' => dirname(__DIR__) . '/data/one-page.html',
            'page'     => 1,
        ];
        yield 'many-page' => [
            'resource' => dirname(__DIR__) . '/data/many-page.html',
            'page'     => 1,
        ];
        yield 'many-page-not-first' => [
            'resource' => dirname(__DIR__) . '/data/many-page-not-first.html',
            'page'     => 6,
        ];
        yield 'rewind-not-first' => [
            'resource' => dirname(__DIR__) . '/data/rewind-not-first.html',
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
            'resource' => dirname(__DIR__) . '/data/one-page.html',
            'pages'    => 1,
        ];
        yield 'many-page' => [
            'resource' => dirname(__DIR__) . '/data/many-page.html',
            'pages'    => 6657,
        ];
        yield 'many-page-not-first' => [
            'resource' => dirname(__DIR__) . '/data/many-page-not-first.html',
            'pages'    => 58,
        ];
        yield 'rewind-not-first' => [
            'resource' => dirname(__DIR__) . '/data/rewind-not-first.html',
            'pages'    => 2,
        ];
    }

    public function getListLawyersProvider(): Iterator
    {
        yield 'one-page' => [
            'resource' => dirname(__DIR__) . '/data/one-page.html',
            'count'    => 12,
        ];
        yield 'many-page' => [
            'resource' => dirname(__DIR__) . '/data/many-page.html',
            'count'    => 20,
        ];
        yield 'many-page-not-first' => [
            'resource' => dirname(__DIR__) . '/data/many-page-not-first.html',
            'count'    => 20,
        ];
        yield 'rewind-not-first' => [
            'resource' => dirname(__DIR__) . '/data/rewind-not-first.html',
            'count'    => 8,
        ];
        yield 'web' => [
            'resource' => 'http://lawyers.minjust.ru/Lawyers',
            'count'    => 20,
        ];
    }

    /**
     * @dataProvider getFullLawyerProvider
     *
     * @param string                                $resource
     * @param \SomeWork\Minjust\Entity\DetailLawyer $exampleLawyer
     *
     * @throws \PHPHtmlParser\Exceptions\ChildNotFoundException
     * @throws \PHPHtmlParser\Exceptions\CircularException
     * @throws \PHPHtmlParser\Exceptions\CurlException
     * @throws \PHPHtmlParser\Exceptions\StrictException
     * @throws \ReflectionException
     */
    public function testGetFullLawyer(string $resource, DetailLawyer $exampleLawyer): void
    {
        $dom = (new Dom())->load($resource);
        /**
         * @var DetailLawyer $lawyer
         */
        $lawyer = $this->invokeMethod(static::$parser, 'getFullLawyer', [$dom]);

        $this->assertEquals($exampleLawyer->getChamberOfLaw(), $lawyer->getChamberOfLaw());

        $exampleLawFormation = $exampleLawyer->getLawFormation();
        $lawFormation = $lawyer->getLawFormation();
        $this->assertInstanceOf(LawFormation::class, $lawFormation);
        $this->assertEquals($exampleLawFormation->getOrganizationalForm(), $lawFormation->getOrganizationalForm());
        $this->assertEquals($exampleLawFormation->getName(), $lawFormation->getName());
        $this->assertEquals($exampleLawFormation->getEmail(), $lawFormation->getEmail());
        $this->assertEquals($exampleLawFormation->getPhone(), $lawFormation->getPhone());
    }

    public function getFullLawyerProvider(): Iterator
    {
        yield 'web' => [
            'resource' => 'http://lawyers.minjust.ru/lawyers/show/1532185',
            'lawyer'   => (new DetailLawyer())
                ->setChamberOfLaw('Адвокатская палата Амурской области')
                ->setLawFormation(
                    (new LawFormation())
                        ->setOrganizationalForm('Адвокатские кабинеты')
                        ->setName('Адвокатский кабинет')
                        ->setAddress('')
                        ->setPhone('. 89098119329')
                        ->setEmail('E-mail: zkabityak@mail.ru')
                ),
        ];
    }
}
