<?php

declare(strict_types=1);

namespace SomeWork\Minjust\Tests\Unit;

use Generator;
use PHPUnit\Framework\TestCase;
use ReflectionObject;
use SomeWork\Minjust\Entity\Lawyer;
use SomeWork\Minjust\FindResponse;

/**
 * @covers \SomeWork\Minjust\FindResponse
 * @coversDefaultClass \SomeWork\Minjust\FindResponse
 */
class FindResponseTest extends TestCase
{
    public function testEmpty(): FindResponse
    {
        $response = new FindResponse();

        $this->assertIsArray($this->getPropertyValue($response, 'lawyers'));
        $this->assertNull($this->getPropertyValue($response, 'detailLawyers'));
        $this->assertIsInt($this->getPropertyValue($response, 'page'));
        $this->assertIsInt($this->getPropertyValue($response, 'totalPage'));
        $this->assertIsInt($this->getPropertyValue($response, 'total'));

        return $response;
    }

    protected function getPropertyValue(object $object, string $property)
    {
        $ref = new ReflectionObject($object);
        /** @noinspection PhpUnhandledExceptionInspection */
        $property = $ref->getProperty($property);
        $property->setAccessible(true);

        return $property->getValue($object);
    }

    /**
     * @depends testEmpty
     *
     * @param \SomeWork\Minjust\FindResponse $response
     *
     * @return \SomeWork\Minjust\FindResponse
     */
    public function testSet(FindResponse $response): FindResponse
    {
        $lawyers = [
            (new Lawyer())->setFullName('test1'),
            (new Lawyer())->setFullName('test2'),
        ];
        $generator = $this->getDetailGenerator($lawyers);

        $response
            ->setLawyers($lawyers)
            ->setDetailLawyersGenerator($generator)
            ->setPage(2)
            ->setTotalPage(3)
            ->setTotal(4);

        $this->assertEquals($lawyers, $this->getPropertyValue($response, 'lawyers'));
        $this->assertEquals($generator, $this->getPropertyValue($response, 'detailLawyers'));
        $this->assertEquals(2, $this->getPropertyValue($response, 'page'));
        $this->assertEquals(3, $this->getPropertyValue($response, 'totalPage'));
        $this->assertEquals(4, $this->getPropertyValue($response, 'total'));

        return $response;
    }

    protected function getDetailGenerator(array $lawyers): ?Generator
    {
        foreach ($lawyers as $lawyer) {
            yield $lawyer;
        }
    }

    /**
     * @depends testSet
     *
     * @param \SomeWork\Minjust\FindResponse $response
     */
    public function testGet(FindResponse $response): void
    {
        $lawyers = [
            (new Lawyer())->setFullName('test1'),
            (new Lawyer())->setFullName('test2'),
        ];
        $generator = $this->getDetailGenerator($lawyers);

        $this->assertEquals($lawyers, $response->getLawyers());
        $this->assertEquals($generator, $response->getDetailLawyers());
        $this->assertEquals(2, $response->getPage());
        $this->assertEquals(3, $response->getTotalPage());
        $this->assertEquals(4, $response->getTotal());
    }

    /**
     * @covers ::addLawyer
     */
    public function testAddLawyer(): void
    {
        $lawyer = (new Lawyer())->setFullName('test1');

        $response = new FindResponse();
        $response->addLawyer($lawyer);

        $this->assertEquals([$lawyer], $this->getPropertyValue($response, 'lawyers'));
    }
}
