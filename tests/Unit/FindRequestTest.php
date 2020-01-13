<?php

declare(strict_types=1);

namespace SomeWork\Minjust\Tests\Unit;

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

        $this->assertEquals(0, $this->getPropertyValue($request, 'territorialSubject'));

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
            ->setTerritorialSubject('01')
            ->setCertificateNumber('testCertificateNumber')
            ->setRegisterNumber('testRegisterNumber')
            ->setStatus(1)
            ->setFullName('testFullName')
            ->setFullData()
            ->setFormOfLegalPractice(2)
            ->setPage(123);

        $this->assertEquals(1, $this->getPropertyValue($request, 'territorialSubject'));
        $this->assertEquals('testCertificateNumber', $this->getPropertyValue($request, 'certificateNumber'));
        $this->assertEquals('testRegisterNumber', $this->getPropertyValue($request, 'registerNumber'));
        $this->assertEquals(1, $this->getPropertyValue($request, 'status'));
        $this->assertEquals('testFullName', $this->getPropertyValue($request, 'fullName'));
        $this->assertEquals(true, $this->getPropertyValue($request, 'fullData'));
        $this->assertEquals(2, $this->getPropertyValue($request, 'formOfLegalPractice'));
        $this->assertEquals(123, $this->getPropertyValue($request, 'page'));

        return $request;
    }

    /**
     * @depends testSet
     *
     * @param \SomeWork\Minjust\FindRequest $request
     *
     * @return \SomeWork\Minjust\FindRequest
     */
    public function testGet(FindRequest $request): FindRequest
    {
        $this->assertEquals('01', $request->getTerritorialSubject());
        $this->assertIsString($request->getTerritorialSubject());

        $this->assertEquals('testCertificateNumber', $request->getCertificateNumber());
        $this->assertIsString($request->getCertificateNumber());

        $this->assertEquals('testRegisterNumber', $request->getRegisterNumber());
        $this->assertIsString($request->getRegisterNumber());

        $this->assertEquals(1, $request->getStatus());
        $this->assertIsInt($request->getStatus());

        $this->assertEquals('testFullName', $request->getFullName());
        $this->assertIsString($request->getFullName());

        $this->assertEquals(true, $request->isFullData());
        $this->assertIsBool($request->isFullData());

        $this->assertEquals(2, $request->getFormOfLegalPractice());
        $this->assertIsInt($request->getFormOfLegalPractice());

        $this->assertEquals(123, $request->getPage());
        $this->assertIsInt($request->getPage());

        return $request;
    }

    /**
     * @depends testSet
     * @covers ::getFormData
     *
     * @param \SomeWork\Minjust\FindRequest $findRequest
     */
    public function testGetFormData(FindRequest $findRequest): void
    {
        $request = [
            FindRequest::FULL_NAME              => $findRequest->getFullName(),
            FindRequest::REGISTER_NUMBER        => $findRequest->getRegisterNumber(),
            FindRequest::CERTIFICATE_NUMBER     => $findRequest->getCertificateNumber(),
            FindRequest::STATUS                 => $findRequest->getStatus(),
            FindRequest::FORM_OF_LEGAL_PRACTICE => $findRequest->getFormOfLegalPractice(),
            FindRequest::TERRITORIAL_SUBJECT    => $findRequest->getTerritorialSubject(),
            FindRequest::PAGE                   => $findRequest->getPage() - 1,
        ];

        $this->assertEquals($request, $findRequest->getFormData());
    }
}
