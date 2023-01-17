<?php

namespace App\Tests\JiraStatistics\Writer;

use App\JiraStatistics\Mapper\MapperInterface;
use App\JiraStatistics\SprintStatistics;
use App\JiraStatistics\Writer\InfluxDB2Writer;
use InfluxDB2\Client;
use InfluxDB2\WriteApi;
use PHPUnit\Framework\TestCase;

class InfluxDB2WriterTest extends TestCase
{
    private $writerApiMock;
    private $clientApiMock;
    private $statsMock;

    public function setUp(): void
    {
        $this->writerApiMock = $this->getMockBuilder(WriteApi::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->clientApiMock = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->clientApiMock->method('createWriteApi')
            ->willReturn($this->writerApiMock);
        $this->statsMock = $this->getMockBuilder(SprintStatistics::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function tearDown(): void
    {
        // TODO: Change the autogenerated stub
        $this->clientApiMock = null;
        $this->writerApiMock = null;
        $this->statsMock = null;
    }

    public function testWriterInstanceNothingSet()
    {
        $this->writerApiMock->expects($this->never())->method('write');
        $this->clientApiMock->expects($this->never())
            ->method('createWriteApi');

        $sut = new InfluxDB2Writer($this->clientApiMock);
        $sut->setBucket('TestBucket');
        $sut->setOrga('TestOrga');
        $sut->writeData($this->statsMock);
    }

    public function testWriterInstanceOneWrite()
    {
        $this->writerApiMock->expects($this->once())
            ->method('write');
        $this->clientApiMock->expects($this->once())
            ->method('createWriteApi');

        $mapperMock = $this->getMockBuilder(MapperInterface::class)
            ->getMock();
        $sut = new InfluxDB2Writer($this->clientApiMock);
        $sut->addStatisticsMapper($mapperMock);
        $sut->writeData($this->statsMock);
    }
}
