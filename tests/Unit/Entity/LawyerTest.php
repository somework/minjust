<?php

declare(strict_types=1);

namespace SomeWork\Minjust\Tests\Unit\Entity;

use PHPUnit\Framework\TestCase;
use ReflectionObject;
use SomeWork\Minjust\Entity\Lawyer;

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
        $this->assertIsString($this->getPropertyValue($lawyer, 'territorialSubject'));
        $this->assertIsString($this->getPropertyValue($lawyer, 'url'));

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
     * @param \SomeWork\Minjust\Entity\Lawyer $lawyer
     *
     * @return \SomeWork\Minjust\Entity\Lawyer
     */
    public function testSet(Lawyer $lawyer): Lawyer
    {
        $lawyer
            ->setUrl('testUrl')
            ->setFullName('testFullName')
            ->setStatus('testStatus')
            ->setRegisterNumber('testRegisterNumber')
            ->setCertificateNumber('testCertificateNumber')
            ->setTerritorialSubject('testTerritorialSubject');

        $this->assertEquals('testUrl', $this->getPropertyValue($lawyer, 'url'));
        $this->assertEquals('testFullName', $this->getPropertyValue($lawyer, 'fullName'));
        $this->assertEquals('testStatus', $this->getPropertyValue($lawyer, 'status'));
        $this->assertEquals('testRegisterNumber', $this->getPropertyValue($lawyer, 'registerNumber'));
        $this->assertEquals('testCertificateNumber', $this->getPropertyValue($lawyer, 'certificateNumber'));
        $this->assertEquals('testTerritorialSubject', $this->getPropertyValue($lawyer, 'territorialSubject'));

        return $lawyer;
    }

    /**
     * @depends testSet
     *
     * @param \SomeWork\Minjust\Entity\Lawyer $lawyer
     */
    public function testGet(Lawyer $lawyer): void
    {
        $this->assertEquals('testUrl', $lawyer->getUrl());
        $this->assertEquals('testFullName', $lawyer->getFullName());
        $this->assertEquals('testStatus', $lawyer->getStatus());
        $this->assertEquals('testRegisterNumber', $lawyer->getRegisterNumber());
        $this->assertEquals('testCertificateNumber', $lawyer->getCertificateNumber());
        $this->assertEquals('testTerritorialSubject', $lawyer->getTerritorialSubject());
    }
}
