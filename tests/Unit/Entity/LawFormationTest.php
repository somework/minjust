<?php

declare(strict_types=1);

namespace SomeWork\Minjust\Tests\Unit\Entity;

use PHPUnit\Framework\TestCase;
use ReflectionObject;
use SomeWork\Minjust\Entity\LawFormation;

/**
 * @covers \SomeWork\Minjust\Entity\LawFormation
 * @coversDefaultClass LawFormation
 */
class LawFormationTest extends TestCase
{
    public function testEmpty(): LawFormation
    {
        $lawFormation = new LawFormation();

        $this->assertIsString($this->getPropertyValue($lawFormation, 'organizationalForm'));
        $this->assertIsString($this->getPropertyValue($lawFormation, 'name'));
        $this->assertIsString($this->getPropertyValue($lawFormation, 'address'));
        $this->assertIsString($this->getPropertyValue($lawFormation, 'phone'));
        $this->assertIsString($this->getPropertyValue($lawFormation, 'email'));

        return $lawFormation;
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
     * @param LawFormation $lawFormation
     *
     * @return LawFormation
     */
    public function testSet(LawFormation $lawFormation): LawFormation
    {
        $lawFormation
            ->setName('testName')
            ->setEmail('testEmail')
            ->setAddress('testAddress')
            ->setPhone('testPhone')
            ->setOrganizationalForm('testOrganizationalForm');

        $this->assertEquals('testName', $this->getPropertyValue($lawFormation, 'name'));
        $this->assertEquals('testEmail', $this->getPropertyValue($lawFormation, 'email'));
        $this->assertEquals('testAddress', $this->getPropertyValue($lawFormation, 'address'));
        $this->assertEquals('testPhone', $this->getPropertyValue($lawFormation, 'phone'));
        $this->assertEquals('testOrganizationalForm', $this->getPropertyValue($lawFormation, 'organizationalForm'));

        return $lawFormation;
    }

    /**
     * @depends testSet
     *
     * @param LawFormation $lawFormation
     */
    public function testGet(LawFormation $lawFormation): void
    {
        $this->assertEquals('testName', $lawFormation->getName());
        $this->assertEquals('testEmail', $lawFormation->getEmail());
        $this->assertEquals('testAddress', $lawFormation->getAddress());
        $this->assertEquals('testPhone', $lawFormation->getPhone());
        $this->assertEquals('testOrganizationalForm', $lawFormation->getOrganizationalForm());
    }
}
