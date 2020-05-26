<?php

/** @noinspection PhpUnhandledExceptionInspection */
declare(strict_types=1);

namespace SomeWork\Minjust\Tests\Unit;

use Exception;
use Generator;
use Iterator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;
use SomeWork\Minjust\Client;
use SomeWork\Minjust\Entity\DetailLawyer;
use SomeWork\Minjust\Entity\LawFormation;
use SomeWork\Minjust\Entity\Lawyer;
use SomeWork\Minjust\Entity\Location;
use SomeWork\Minjust\FindRequest;
use SomeWork\Minjust\FindResponse;
use SomeWork\Minjust\Parser\ParserInterface;
use SomeWork\Minjust\Service;

/**
 * @coversDefaultClass \SomeWork\Minjust\Service
 */
class ServiceTest extends TestCase
{
    /**
     * @covers ::__construct
     */
    public function testConstruct(): void
    {
        $client = $this->createMock(Client::class);
        $parser = $this->createMock(ParserInterface::class);

        $service = new Service($client, $parser);

        $ref = new ReflectionClass(Service::class);

        $privateClient = $ref->getProperty('client');
        $privateClient->setAccessible(true);

        $privateParser = $ref->getProperty('parser');
        $privateParser->setAccessible(true);

        $this->assertEquals($client, $privateClient->getValue($service));
        $this->assertEquals($parser, $privateParser->getValue($service));
    }

    /**
     * @covers ::findAll
     */
    public function testFindAll(): void
    {
        $request = new FindRequest();

        $fn = static function () {
            yield 1;
        };

        /**
         * @var MockObject|Service $service
         */
        $service = $this->createPartialMock(Service::class, ['findFromTo']);

        $service
            ->expects($this->once())
            ->method('findFromTo')
            ->with($request, 1, 0)
            ->willReturn($fn());

        $service->findAll($request);
    }

    /**
     * @covers ::find
     */
    public function testFind(): void
    {
        /**
         * @var ParserInterface|MockObject $parser
         * @var Service|MockObject         $service
         * @var Client|MockObject          $client
         */
        $lawyer = $this->generateLawyer();

        $response = (new FindResponse())
            ->addLawyer($lawyer)
            ->setPage(1)
            ->setTotalPage(1)
            ->setTotal(1);

        $client = $this->createMock(Client::class);
        $client
            ->method('list')
            ->willReturn('');

        $parser = $this->createMock(ParserInterface::class);
        $parser
            ->method('list')
            ->willReturn($response);

        $service = $this
            ->getMockBuilder(Service::class)
            ->onlyMethods(['getDetailLawyersGenerator'])
            ->setConstructorArgs([$client, $parser])
            ->getMock();

        $service
            ->method('getDetailLawyersGenerator')
            ->willReturn($this->generateDetailLawyerGenerator($lawyer));

        $response = $service->find(new FindRequest());

        $this->assertCount(1, $response->getLawyers());
        $this->assertInstanceOf(Lawyer::class, $response->getLawyers()[0]);
        $this->assertInstanceOf(Generator::class, $response->getDetailLawyers());
    }

    protected function generateLawyer(): Lawyer
    {
        try {
            $id = md5(random_bytes(16));
        } catch (Exception $exception) {
            $id = md5(microtime(true));
        }

        $location = (new Location())
            ->setId('01')
            ->setName('Тестовая территория');

        return (new Lawyer())
            ->setLocation($location)
            ->setCertificateNumber('test/' . $id)
            ->setRegisterNumber('test/' . $id)
            ->setStatus('Тестовый статус')
            ->setFullName('Тестовый Тест Тестович')
            ->setUrl('/test/' . $id . '/');
    }

    /**
     * @param Lawyer $lawyer
     *
     * @return Generator
     */
    protected function generateDetailLawyerGenerator(Lawyer $lawyer): Generator
    {
        yield new DetailLawyer($lawyer);
    }

    /**
     * @covers ::findFromTo
     */
    public function testFindFromToOffsetForOnePage(): void
    {
        $lawyer = $this->generateLawyer();

        $response = (new FindResponse())
            ->setTotal(1)
            ->setTotalPage(1)
            ->setPage(1)
            ->addLawyer($lawyer)
            ->setDetailLawyersGenerator(
                $this->generateDetailLawyerGenerator($lawyer)
            );

        $service = $this
            ->createPartialMock(Service::class, ['find']);
        $service
            ->method('find')
            ->willReturn($response);

        $request = new FindRequest();

        $response = $service->findFromTo($request, 1, 2);
        $this->assertInstanceOf(Generator::class, $response);

        foreach ($response as $lawyer) {
            $this->assertNotInstanceOf(DetailLawyer::class, $lawyer);
        }

        $request->setFullData(true);
        $response = $service->findFromTo($request, 1, 1);

        $this->assertContainsOnlyInstancesOf(DetailLawyer::class, $response);
    }

    /**
     * @covers ::findFromTo
     * @dataProvider multipleProvider
     *
     * @param bool $isMultiple
     */
    public function testFindFromToOffsetForMultiplePage(bool $isMultiple): void
    {
        $lawyerFirst = $this->generateLawyer();
        $lawyersSecond = $this->generateLawyer();

        $responseFirst = (new FindResponse())
            ->setTotal(1)
            ->setTotalPage(2)
            ->setPage(1)
            ->addLawyer($lawyerFirst)
            ->setDetailLawyersGenerator(
                $this->generateDetailLawyerGenerator($lawyerFirst)
            );

        $responseSecond = (new FindResponse())
            ->setTotal(1)
            ->setTotalPage(2)
            ->setPage(2)
            ->addLawyer($lawyersSecond)
            ->setDetailLawyersGenerator(
                $this->generateDetailLawyerGenerator($lawyersSecond)
            );

        $service = $this
            ->createPartialMock(Service::class, ['find']);

        $service
            ->expects($this->at(0))
            ->method('find')
            ->willReturn($responseFirst);

        $service
            ->expects($this->at(1))
            ->method('find')
            ->willReturn($responseSecond);

        $request = new FindRequest();

        if (!$isMultiple) {
            $response = $service->findFromTo($request, 1, 2);

            $lawyer = $response->current();
            $this->assertInstanceOf(Lawyer::class, $lawyer);
            $this->assertEquals($lawyerFirst, $lawyer);

            $response->next();

            $lawyer = $response->current();
            $this->assertInstanceOf(Lawyer::class, $lawyer);
            $this->assertEquals($lawyersSecond, $lawyer);

            $response->next();
            $this->assertFalse($response->valid());

            return;
        }

        $request->setFullData(true);
        $response = $service->findFromTo($request, 1, 2);

        $this->assertContainsOnlyInstancesOf(DetailLawyer::class, $response);
    }

    public function multipleProvider(): Iterator
    {
        yield [false];
        yield [true];
    }

    /**
     * @covers ::getDetailLawyersGenerator
     */
    public function testGetDetailLawyersGenerator(): void
    {
        $detailFirst = $this->generateDetailLawyer();
        $detailSecond = $this->generateDetailLawyer();

        $lawyerFirst = $this->generateLawyer();
        $lawyerSecond = $this->generateLawyer();

        $client = $this->createMock(Client::class);
        $client
            ->method('detail')
            ->willReturn('');

        $parser = $this->createMock(ParserInterface::class);
        $parser
            ->expects($this->at(0))
            ->method('detail')
            ->willReturn($detailFirst);

        $parser
            ->expects($this->at(1))
            ->method('detail')
            ->willReturn($detailSecond);

        $service = new Service($client, $parser);
        $generator = $this->invokeMethod($service, 'getDetailLawyersGenerator', [[$lawyerFirst, $lawyerSecond]]);

        $this->assertInstanceOf(Generator::class, $generator);

        /**
         * @var DetailLawyer $lawyer
         */
        $lawyer = $generator->current();
        $this->assertInstanceOf(DetailLawyer::class, $lawyer);
        $this->assertEquals($lawyerFirst->getUrl(), $lawyer->getUrl());
        $this->assertEquals($lawyerFirst->getLocation(), $lawyer->getLocation());
        $this->assertEquals($lawyerFirst->getStatus(), $lawyer->getStatus());
        $this->assertEquals($lawyerFirst->getCertificateNumber(), $lawyer->getCertificateNumber());
        $this->assertEquals($lawyerFirst->getRegisterNumber(), $lawyer->getRegisterNumber());
        $this->assertEquals($lawyerFirst->getFullName(), $lawyer->getFullName());
        $this->assertEquals($detailFirst, $lawyer);

        $generator->next();
        $lawyer = $generator->current();
        $this->assertInstanceOf(DetailLawyer::class, $lawyer);
        $this->assertEquals($lawyerSecond->getUrl(), $lawyer->getUrl());
        $this->assertEquals($lawyerSecond->getLocation(), $lawyer->getLocation());
        $this->assertEquals($lawyerSecond->getStatus(), $lawyer->getStatus());
        $this->assertEquals($lawyerSecond->getCertificateNumber(), $lawyer->getCertificateNumber());
        $this->assertEquals($lawyerSecond->getRegisterNumber(), $lawyer->getRegisterNumber());
        $this->assertEquals($lawyerSecond->getFullName(), $lawyer->getFullName());
        $this->assertEquals($detailSecond, $lawyer);
    }

    protected function generateDetailLawyer(?Lawyer $lawyer = null): DetailLawyer
    {
        try {
            $id = md5(random_bytes(16));
        } catch (Exception $exception) {
            $id = md5(microtime(true));
        }

        return (new DetailLawyer($lawyer))
            ->setChamberOfLaw('Палата ' . $id)
            ->setLawFormation(
                (new LawFormation())
                    ->setPhone('88004443459')
                    ->setAddress('г. Москва, ул. Беговая, д. 2')
                    ->setName('ООО "Бреви Ману" ID: ' . $id)
                    ->setEmail('pinchuk_iv@mnp.ru')
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
     * @throws ReflectionException
     */
    public function invokeMethod($object, $methodName, array $parameters = [])
    {
        $reflection = new ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    /**
     * @covers ::getDetailLawyersGenerator
     */
    public function testGetDetailLawyersGeneratorOnEmpty(): void
    {
        $client = $this->createMock(Client::class);
        $parser = $this->createMock(ParserInterface::class);

        $service = new Service($client, $parser);

        /**
         * @var Generator $generator
         */
        $generator = $this->invokeMethod($service, 'getDetailLawyersGenerator', [[]]);
        $this->assertInstanceOf(Generator::class, $generator);
        $this->assertFalse($generator->valid());
    }
}
