<?php

namespace App\JiraStatistics\Mapper\InfluxDB2;

use App\JiraStatistics\Aggregator\AggregationItem;
use App\JiraStatistics\Aggregator\IssueAggregator;

trait AggregationItemValidateTrait
{
    private function isValidCountStoryPointItem(AggregationItem $item): bool
    {
        $value = $item->getValue();
        return (
            is_array($value) &&
            isset($value[IssueAggregator::AGGREGATION_COUNT]) &&
            isset($value[IssueAggregator::AGGREGATION_STORYPOINT])
        );
    }
}
