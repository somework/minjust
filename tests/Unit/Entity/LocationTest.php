<?php

namespace SomeWork\Minjust\Tests\Unit\Entity;

use PHPUnit\Framework\TestCase;
use ReflectionObject;
use SomeWork\Minjust\Entity\Location;

class LocationTest extends TestCase
{
    public function testEmpty(): Location
    {
        $location = new Location();
        $this->assertIsString($this->getPropertyValue($location, 'id'));
        $this->assertIsString($this->getPropertyValue($location, 'name'));

        return $location;
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
     * @param Location $location
     *
     * @return Location
     */
    public function testSet(Location $location): Location
    {
        $location->setId('444555');
        $location->setName('666777');

        $this->assertEquals('444555', $this->getPropertyValue($location, 'id'));
        $this->assertEquals('666777', $this->getPropertyValue($location, 'name'));

        return $location;
    }

    /**
     * @depends testSet
     *
     * @param Location $location
     */
    public function testGet(Location $location): void
    {
        $this->assertEquals('444555', $location->getId());
        $this->assertEquals('666777', $location->getName());
    }
}
