<?php

declare(strict_types=1);

namespace SomeWork\Minjust\Tests\Unit\Entity;

use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionObject;
use SomeWork\Minjust\Entity\DetailLawyer;
use SomeWork\Minjust\Entity\LawFormation;
use SomeWork\Minjust\Entity\Lawyer;

/**
 * @coversDefaultClass \SomeWork\Minjust\Entity\DetailLawyer
 * @covers \SomeWork\Minjust\Entity\DetailLawyer
 */
class DetailLawyerTest extends TestCase
{
    public function testEmpty(): DetailLawyer
    {
        $lawyer = new DetailLawyer();
        $this->assertIsString($this->getPropertyValue($lawyer, 'chamberOfLaw'));
        $this->assertNull($this->getPropertyValue($lawyer, 'lawFormation'));

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
     * @param \SomeWork\Minjust\Entity\DetailLawyer $lawyer
     *
     * @return \SomeWork\Minjust\Entity\DetailLawyer
     */
    public function testSet(DetailLawyer $lawyer): DetailLawyer
    {
        $lawFormation = (new LawFormation())->setName('testLawFormation');

        $lawyer
            ->setChamberOfLaw('testChamberOfLaw')
            ->setLawFormation($lawFormation);

        $this->assertEquals('testChamberOfLaw', $this->getPropertyValue($lawyer, 'chamberOfLaw'));
        $this->assertEquals($lawFormation, $this->getPropertyValue($lawyer, 'lawFormation'));

        return $lawyer;
    }

    /**
     * @depends testSet
     *
     * @param \SomeWork\Minjust\Entity\DetailLawyer $lawyer
     */
    public function testGet(DetailLawyer $lawyer): void
    {
        $lawFormation = $this->getPropertyValue($lawyer, 'lawFormation');

        $this->assertEquals('testChamberOfLaw', $lawyer->getChamberOfLaw());
        $this->assertEquals($lawFormation, $lawyer->getLawFormation());
    }

    public function testLoadFromLawyer(): void
    {
        $lawyer = (new Lawyer())
            ->setUrl('testUrl')
            ->setFullName('testFullName')
            ->setStatus('testStatus')
            ->setRegisterNumber('testRegisterNumber')
            ->setCertificateNumber('testCertificateNumber')
            ->setTerritorialSubject('testTerritorialSubject');

        $detailLawyer = (new DetailLawyer())->loadFromLawyer($lawyer);

        $this->assertEquals($lawyer->getUrl(), $detailLawyer->getUrl());
        $this->assertEquals($lawyer->getFullName(), $detailLawyer->getFullName());
        $this->assertEquals($lawyer->getStatus(), $detailLawyer->getStatus());
        $this->assertEquals($lawyer->getRegisterNumber(), $detailLawyer->getRegisterNumber());
        $this->assertEquals($lawyer->getCertificateNumber(), $detailLawyer->getCertificateNumber());
        $this->assertEquals($lawyer->getTerritorialSubject(), $detailLawyer->getTerritorialSubject());
    }

    /**
     * @covers ::__construct
     */
    public function testConstruct(): void
    {
        $lawyer = (new Lawyer())
            ->setUrl('testUrl')
            ->setFullName('testFullName')
            ->setStatus('testStatus')
            ->setRegisterNumber('testRegisterNumber')
            ->setCertificateNumber('testCertificateNumber')
            ->setTerritorialSubject('testTerritorialSubject');

        $detailLawyer = $this->createPartialMock(DetailLawyer::class, ['loadFromLawyer']);

        $detailLawyer
            ->expects($this->once())
            ->method('loadFromLawyer')
            ->with($lawyer)
            ->willReturn($detailLawyer);

        $reflectedClass = new ReflectionClass(DetailLawyer::class);
        $constructor = $reflectedClass->getConstructor();
        $constructor->invoke($detailLawyer, $lawyer);
    }
}
