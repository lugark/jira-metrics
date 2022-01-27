<?php

namespace App\Tests\JiraStatistics\Aggregator;

use App\JiraStatistics\Aggregator\AggregationResult;
use App\JiraStatistics\Aggregator\AggregatorAwareTrait;
use App\JiraStatistics\Aggregator\AggregatorException;
use App\JiraStatistics\Aggregator\AggregatorInterface;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Throwable;

class AggregatorAwareTraitTest extends TestCase
{
    public function testAddAggregator()
    {
        $sut = $this->getObjectForTrait(AggregatorAwareTrait::class);
        $mockAggregator = $this->getMockBuilder(AggregatorInterface::class)
            ->getMock();
        $mockAggregator->method('getName')->willReturn('Test');
        $mockAggregator->method('execute')->willReturn(new AggregationResult());

        $sut->addAggregator($mockAggregator);
        $result = $sut->runAggregator('Test', 'test', []);
        Assert::assertEquals(0, $result->count());
    }

    public function testNoAggregatorFound()
    {
        $this->expectException(AggregatorException::class);
        $this->expectExceptionMessage('Could not find aggregator');
        $sut = $this->getObjectForTrait(AggregatorAwareTrait::class);
        $sut->runAggregator('NotFoundAggregator', 'test', []);
    }

    public function testExecuteError()
    {
        $this->expectException(AggregatorException::class);
        $this->expectExceptionMessage('test');

        $sut = $this->getObjectForTrait(AggregatorAwareTrait::class);
        $mockAggregator = $this->getMockBuilder(AggregatorInterface::class)
            ->getMock();
        $mockAggregator->method('getName')->willReturn('Test');
        $mockAggregator->method('execute')->willThrowException(new \Exception('test'));

        $sut->addAggregator($mockAggregator);
        $sut->runAggregator('Test', 'test', []);
    }

    public function testResetAggregator()
    {
        $this->expectException(AggregatorException::class);
        $this->expectExceptionMessage('Could not find aggregator');

        $sut = $this->getObjectForTrait(AggregatorAwareTrait::class);
        $mockAggregator = $this->getMockBuilder(AggregatorInterface::class)
            ->getMock();
        $mockAggregator->method('getName')->willReturn('Test');
        $mockAggregator->method('execute')->willReturn(new AggregationResult());

        $sut->addAggregator($mockAggregator);
        $sut->resetAggregator();
        $sut->runAggregator('Test', 'test', []);
    }

}
