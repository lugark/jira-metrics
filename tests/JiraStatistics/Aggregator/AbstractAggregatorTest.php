<?php

namespace App\Tests\JiraStatistics\Aggregator;

use App\JiraStatistics\Aggregator\AbstractAggregator;
use App\JiraStatistics\Aggregator\AggregationResult;
use App\JiraStatistics\Aggregator\AggregatorException;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class AggregationStub extends AbstractAggregator
{
    public function getName(): string
    {
        return 'TestAggreagator';
    }

    public function testCount()
    {
        return new AggregationResult();
    }

    public function testFail()
    {
        return ['this', 'should', 'fail'];
    }
}

class AbstractAggregatorTest extends TestCase
{
    public function testExecute()
    {
        $sut = new AggregationStub();
        $result = $sut->execute('testCount', []);

        Assert::assertEquals(0, count($result));
    }

    public function testNoAggregationResultReturned()
    {
        $this->expectException(\TypeError::class);
        $sut = new AggregationStub();
        $sut->execute('testFail', []);
    }

    public function testFailAggregationNotFound()
    {
        $this->expectExceptionMessage('Can not find aggregation');
        $this->expectException(AggregatorException::class);
        $sut = new AggregationStub();
        $sut->execute('NotFoundAggregation', []);
    }
}
