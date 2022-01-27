<?php

namespace App\Tests\JiraStatistics\Aggregator;

use App\JiraStatistics\Aggregator\AggregationItem;
use App\JiraStatistics\Aggregator\AggregationResult;
use App\JiraStatistics\Aggregator\AggregatorException;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class AggregationResultTest extends TestCase
{
    public function testAggregationResult()
    {
        $sut = new AggregationResult();
        Assert::assertEquals(0, count($sut));

        $sut->addItem(new AggregationItem('test', 1));
        Assert::assertEquals(1, count($sut));
        Assert::assertEquals(new AggregationItem('test', 1), $sut->current());

        $sut->setValueByKey('test', 3);
        Assert::assertEquals(3, $sut->getByKey('test')->getValue());

        $sut->increase('test');
        Assert::assertEquals(1, count($sut));
        Assert::assertEquals(new AggregationItem('test', 4), $sut->current());

        $sut->increase('testNotAdded');
        Assert::assertEquals(2, count($sut));
        Assert::assertEquals(1, $sut->getByKey('testNotAdded')->getValue());

        $sut->decrease('testNotAdded2');
        Assert::assertEquals(3, count($sut));
        Assert::assertEquals(-1, $sut->getByKey('testNotAdded2')->getValue());

        Assert::assertEquals(['test', 'testNotAdded', 'testNotAdded2'], $sut->getKeys());
        Assert::assertEquals('notFound', $sut->getByKey('NotFoundKey', 'notFound'));
    }

    public function testFailIncrease()
    {
        $this->expectException(AggregatorException::class);
        $this->expectExceptionMessage('Trying to increase non Integer value');

        $sut = new AggregationResult();
        $sut->addItem(new AggregationItem('Test', ['one'=>1, 'two'=>2]));
        $sut->increase('Test');
    }

    public function testFailDecrease()
    {
        $this->expectException(AggregatorException::class);
        $this->expectExceptionMessage('Trying to decrease non Integer value');

        $sut = new AggregationResult();
        $sut->addItem(new AggregationItem('Test', ['one'=>1, 'two'=>2]));
        $sut->decrease('Test');
    }

    public function testAggregationResultArray()
    {
        $sut = new AggregationResult();
        Assert::assertEquals(0, count($sut));

        $sut->addItem(new AggregationItem('test1', 1));
        $sut->addItem(new AggregationItem('test2', 2));
        $sut->addItem(new AggregationItem('test3', 3));

        $sut->next();
        Assert::assertEquals('test2', $sut->current()->getKey());
        Assert::assertEquals('test2', $sut->key());
        $sut->next();
        Assert::assertEquals('test3', $sut->current()->getKey());
        Assert::assertEquals('test3', $sut->key());

        $sut->next();
        Assert::assertFalse($sut->valid());

        $sut->rewind();
        Assert::assertEquals('test1', $sut->current()->getKey());
    }

}
