<?php

namespace SomeWork\Minjust\Tests\Unit;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use ReflectionObject;
use SomeWork\Minjust\FindRequest;

/**
 * @covers \SomeWork\Minjust\FindRequest
 * @coversDefaultClass \SomeWork\Minjust\FindRequest
 */
class FindRequestTest extends TestCase
{
    public function testEmpty(): FindRequest
    {
        $request = new FindRequest();

        $this->assertIsString($this->getPropertyValue($request, 'fullName'));
        $this->assertIsString($this->getPropertyValue($request, 'registerNumber'));
        $this->assertIsString($this->getPropertyValue($request, 'certificateNumber'));

        $this->assertNull($this->getPropertyValue($request, 'status'));
        $this->assertNull($this->getPropertyValue($request, 'formOfLegalPractice'));
        $this->assertNull($this->getPropertyValue($request, 'territorialSubject'));

        $this->assertIsBool($this->getPropertyValue($request, 'fullData'));

        $this->assertIsInt($this->getPropertyValue($request, 'max'));
        $this->assertEquals(FindRequest::MAX_VALUE_MAX, $this->getPropertyValue($request, 'max'));

        $this->assertIsInt($this->getPropertyValue($request, 'offset'));

        return $request;
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
     * @param \SomeWork\Minjust\FindRequest $request
     *
     * @return \SomeWork\Minjust\FindRequest
     */
    public function testSet(FindRequest $request): FindRequest
    {
        $request
            ->setTerritorialSubject(1)
            ->setCertificateNumber('testCertificateNumber')
            ->setRegisterNumber('testRegisterNumber')
            ->setStatus(1)
            ->setFullName('testFullName')
            ->setFullData()
            ->setMax(55)
            ->setOffset(1)
            ->setFormOfLegalPractice(2);

        $this->assertEquals(1, $this->getPropertyValue($request, 'territorialSubject'));
        $this->assertEquals('testCertificateNumber', $this->getPropertyValue($request, 'certificateNumber'));
        $this->assertEquals('testRegisterNumber', $this->getPropertyValue($request, 'registerNumber'));
        $this->assertEquals(1, $this->getPropertyValue($request, 'status'));
        $this->assertEquals('testFullName', $this->getPropertyValue($request, 'fullName'));
        $this->assertEquals(true, $this->getPropertyValue($request, 'fullData'));
        $this->assertEquals(55, $this->getPropertyValue($request, 'max'));
        $this->assertEquals(1, $this->getPropertyValue($request, 'offset'));
        $this->assertEquals(2, $this->getPropertyValue($request, 'formOfLegalPractice'));

        return $request;
    }

    /**
     * @depends testSet
     *
     * @param \SomeWork\Minjust\FindRequest $request
     */
    public function testGet(FindRequest $request): void
    {
        $this->assertEquals(1, $request->getTerritorialSubject());
        $this->assertEquals('testCertificateNumber', $request->getCertificateNumber());
        $this->assertEquals('testRegisterNumber', $request->getRegisterNumber());
        $this->assertEquals(1, $request->getStatus());
        $this->assertEquals('testFullName', $request->getFullName());
        $this->assertEquals(true, $request->isFullData());
        $this->assertEquals(55, $request->getMax());
        $this->assertEquals(1, $request->getOffset());
        $this->assertEquals(2, $request->getFormOfLegalPractice());
    }

    /**
     * @covers ::setMax
     */
    public function testMaxMoreInvalidArgumentException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf(
                'Maximum value for "%s" is %s',
                FindRequest::MAX,
                FindRequest::MAX_VALUE_MAX
            ));
        (new FindRequest())->setMax(FindRequest::MAX_VALUE_MAX + 1);
    }

    /**
     * @covers ::setMax
     */
    public function testMaxLessInvalidArgumentException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf(
                'Minimum value for "%s" is %s',
                FindRequest::MAX,
                1
            )
        );
        (new FindRequest())->setMax(0);
    }

    /**
     * @covers ::setOffset
     */
    public function testMinOffsetInvalidException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf(
                'Minimum value for "%s" is %s',
                FindRequest::OFFSET,
                0
            )
        );
        (new FindRequest())->setOffset(-1);
    }
}