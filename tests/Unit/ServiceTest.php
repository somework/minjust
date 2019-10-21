<?php

namespace SomeWork\Minjust\Tests\Unit;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
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
        $lawyer = (new Lawyer())
            ->setTerritorialSubject('Тестовая территория')
            ->setCertificateNumber('test/test')
            ->setRegisterNumber('test/test')
            ->setStatus('Тестовый статус')
            ->setFullName('Тестовый Тест Тестович')
            ->setUrl('/test/');

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
            ->onlyMethods(['getFullElementsGenerator'])
            ->setConstructorArgs([$client, $parser])
            ->getMock();

        $fn = static function () use ($lawyer) {
            yield DetailLawyer::init($lawyer);
        };

        $service
            ->method('getFullElementsGenerator')
            ->willReturn($fn());

        $response = $service->find(new FindRequest());

        $this->assertCount(1, $response->getLawyers());
        $this->assertInstanceOf(Lawyer::class, $response->getLawyers()[0]);
        $this->assertInstanceOf(\Generator::class, $response->getDetailLawyers());
    }

    public function testFindFromToOffsetForOnePage(): void
    {
        $lawyer = (new Lawyer())
            ->setTerritorialSubject('Тестовая территория')
            ->setCertificateNumber('test/test')
            ->setRegisterNumber('test/test')
            ->setStatus('Тестовый статус')
            ->setFullName('Тестовый Тест Тестович')
            ->setUrl('/test/');

        $response = new FindResponse();
        $response
            ->setTotal(1)
            ->setTotalPage(1)
            ->setPage(1)
            ->addLawyer($lawyer);

        $service = $this
            ->createPartialMock(Service::class, ['find']);
        $service
            ->method('find')
            ->willReturn($response);

        $request = new FindRequest();
        $request->setOffset(1000);

        $response = $service->findFromTo($request, 1, 2);
        $this->assertInstanceOf(\Generator::class, $response);

        $lawyer = $response->current();
        $this->assertInstanceOf(Lawyer::class, $lawyer);
        $this->assertNotInstanceOf(DetailLawyer::class, $lawyer);
        $this->assertEquals(0, $request->getOffset());

//        $request->setFullData(true);
//        $response = $service->findFromTo($request, 1, 1);
//
//        $lawyer = $response->current();
//        $this->assertInstanceOf(DetailLawyer::class, $lawyer);
    }
}
