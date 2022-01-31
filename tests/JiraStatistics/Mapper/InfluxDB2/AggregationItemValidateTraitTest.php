<?php

namespace App\Tests\JiraStatistics\Mapper\InfluxDB2;

use App\JiraStatistics\Aggregator\AggregationItem;
use App\JiraStatistics\Aggregator\IssueAggregator;
use App\JiraStatistics\Mapper\InfluxDB2\AggregationItemValidateTrait;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class TestObject
{
    use AggregationItemValidateTrait;

    public function testValidCountStoryPointItem($item)
    {
        return $this->isValidCountStoryPointItem($item);
    }
}

class AggregationItemValidateTraitTest extends TestCase
{
    /**
     * @dataProvider getCountStorypointItems()
     */
    public function testValidCountStoryPointItem($item, $result)
    {
        $sut = new TestObject();
        Assert::assertEquals($result, $sut->testValidCountStoryPointItem($item));
    }

    public function getCountStorypointItems()
    {
        $validItem = new AggregationItem('valid', [IssueAggregator::AGGREGATION_COUNT=>0, IssueAggregator::AGGREGATION_STORYPOINT=>1]);

        return [
            'validItem' => [
                new AggregationItem(
                    'valid',
                    [IssueAggregator::AGGREGATION_COUNT=>0, IssueAggregator::AGGREGATION_STORYPOINT=>1]
                ),
                true
            ],
            'invalidItemNoValidKeys' => [new AggregationItem('valid', ['key' => 1, 'key2' => 3]), false],
            'invalidItemNoArray' => [new AggregationItem('key', 'value'), false],
        ];
    }
}
