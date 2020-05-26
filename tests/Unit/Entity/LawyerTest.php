<?php

declare(strict_types=1);

namespace SomeWork\Minjust\Tests\Unit\Entity;

use PHPUnit\Framework\TestCase;
use ReflectionObject;
use SomeWork\Minjust\Entity\Lawyer;
use SomeWork\Minjust\Entity\Location;

/**
 * @covers \SomeWork\Minjust\Entity\Lawyer
 */
class LawyerTest extends TestCase
{
    public function testEmpty(): Lawyer
    {
        $lawyer = new Lawyer();
        $this->assertIsString($this->getPropertyValue($lawyer, 'fullName'));
        $this->assertIsString($this->getPropertyValue($lawyer, 'registerNumber'));
        $this->assertIsString($this->getPropertyValue($lawyer, 'certificateNumber'));
        $this->assertIsString($this->getPropertyValue($lawyer, 'status'));
        $this->assertIsString($this->getPropertyValue($lawyer, 'url'));
        $this->assertNull($this->getPropertyValue($lawyer, 'location'));

        return $lawyer;
    }

    protected function getPropertyValue(object $object, string $property)
    {
        $ref = new ReflectionObject($object);
        $property = $ref->getProperty($property);
        $property->setAccessible(true);

        return $property->getValue($object);
    }

    /**
     * @depends testEmpty
     *
     * @param Lawyer $lawyer
     *
     * @return Lawyer
     */
    public function testSet(Lawyer $lawyer): Lawyer
    {
        $location = (new Location())
            ->setId('testLocationId')
            ->setName('testLocation');

        $lawyer
            ->setUrl('testUrl')
            ->setFullName('testFullName')
            ->setStatus('testStatus')
            ->setRegisterNumber('testRegisterNumber')
            ->setCertificateNumber('testCertificateNumber')
            ->setLocation($location);

        $this->assertEquals('testUrl', $this->getPropertyValue($lawyer, 'url'));
        $this->assertEquals('testFullName', $this->getPropertyValue($lawyer, 'fullName'));
        $this->assertEquals('testStatus', $this->getPropertyValue($lawyer, 'status'));
        $this->assertEquals('testRegisterNumber', $this->getPropertyValue($lawyer, 'registerNumber'));
        $this->assertEquals('testCertificateNumber', $this->getPropertyValue($lawyer, 'certificateNumber'));
        $this->assertEquals($location, $this->getPropertyValue($lawyer, 'location'));

        return $lawyer;
    }

    /**
     * @depends testSet
     *
     * @param Lawyer $lawyer
     */
    public function testGet(Lawyer $lawyer): void
    {
        $this->assertEquals('testUrl', $lawyer->getUrl());
        $this->assertEquals('testFullName', $lawyer->getFullName());
        $this->assertEquals('testStatus', $lawyer->getStatus());
        $this->assertEquals('testRegisterNumber', $lawyer->getRegisterNumber());
        $this->assertEquals('testCertificateNumber', $lawyer->getCertificateNumber());
        $this->assertEquals('testLocationId', $lawyer->getLocation()->getId());
        $this->assertEquals('testLocation', $lawyer->getLocation()->getName());
    }
}
