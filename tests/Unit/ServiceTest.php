<?php
/** @noinspection PhpUnhandledExceptionInspection */

namespace SomeWork\Minjust\Tests\Unit;

use Exception;
use Generator;
use Iterator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use SomeWork\Minjust\Client;
use SomeWork\Minjust\Entity\DetailLawyer;
use SomeWork\Minjust\Entity\Lawyer;
use SomeWork\Minjust\FindRequest;
use SomeWork\Minjust\FindResponse;
use SomeWork\Minjust\Parser\ParserInterface;
use SomeWork\Minjust\Service;

class ServiceTest extends TestCase
{
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

        return (new Lawyer())
            ->setTerritorialSubject('Тестовая территория')
            ->setCertificateNumber('test/' . $id)
            ->setRegisterNumber('test/' . $id)
            ->setStatus('Тестовый статус')
            ->setFullName('Тестовый Тест Тестович')
            ->setUrl('/test/' . $id . '/');
    }

    /**
     * @param \SomeWork\Minjust\Entity\Lawyer $lawyer
     *
     * @return \Generator
     */
    protected function generateDetailLawyerGenerator(Lawyer $lawyer): Generator
    {
        yield DetailLawyer::init($lawyer);
    }

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
        $request->setOffset(1000);

        $response = $service->findFromTo($request, 1, 2);
        $this->assertInstanceOf(Generator::class, $response);

        foreach ($response as $lawyer) {
            $this->assertNotInstanceOf(DetailLawyer::class, $lawyer);
        }
        $this->assertEquals(0, $request->getOffset());

        $request->setFullData(true);
        $response = $service->findFromTo($request, 1, 1);

        $this->assertContainsOnlyInstancesOf(DetailLawyer::class, $response);
    }

    /**
     * @dataProvider multipleProvider
     *
     * @param bool $isMultiple
     *
     * @throws \Psr\Http\Client\ClientExceptionInterface
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

        $request = (new FindRequest())
            ->setMax(1);

        if (!$isMultiple) {
            $response = $service->findFromTo($request, 1, 2);

            $lawyer = $response->current();
            $this->assertInstanceOf(Lawyer::class, $lawyer);
            $this->assertEquals($lawyerFirst, $lawyer);

            $response->next();
            $this->assertEquals(1, $request->getOffset());

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

    public function testGetDetailLawyersGenerator(): void
    {
        $lawyerFirst = DetailLawyer::init($this->generateLawyer());
        $lawyerSecond = DetailLawyer::init($this->generateLawyer());

        $client = $this->createMock(Client::class);
        $client
            ->method('detail')
            ->willReturn('');

        $parser = $this->createMock(ParserInterface::class);
        $parser
            ->expects($this->at(0))
            ->method('detail')
            ->willReturn($lawyerFirst);

        $parser
            ->expects($this->at(1))
            ->method('detail')
            ->willReturn($lawyerSecond);

        $service = new Service($client, $parser);
        $generator = $this->invokeMethod($service, 'getDetailLawyersGenerator', [[$lawyerFirst, $lawyerSecond]]);

        $this->assertInstanceOf(Generator::class, $generator);

        $lawyer = $generator->current();
        $this->assertInstanceOf(DetailLawyer::class, $lawyer);
        $this->assertEquals($lawyerFirst, $lawyer);

        $generator->next();
        $lawyer = $generator->current();
        $this->assertInstanceOf(DetailLawyer::class, $lawyer);
        $this->assertEquals($lawyerSecond, $lawyer);
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

    public function testGetDetailLawyersGeneratorOnEmpty(): void
    {
        $client = $this->createMock(Client::class);
        $parser = $this->createMock(ParserInterface::class);

        $service = new Service($client, $parser);

        /**
         * @var \Generator $generator
         */
        $generator = $this->invokeMethod($service, 'getDetailLawyersGenerator', [[]]);
        $this->assertInstanceOf(Generator::class, $generator);
        $this->assertFalse($generator->valid());
    }
}
