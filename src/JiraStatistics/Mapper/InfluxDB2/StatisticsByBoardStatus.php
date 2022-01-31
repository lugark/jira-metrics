<?php

namespace App\JiraStatistics\Mapper\InfluxDB2;

use App\JiraStatistics\Aggregator\AggregationItem;
use App\JiraStatistics\Aggregator\IssueAggregator;
use App\JiraStatistics\StatisticsInterface;
use App\JiraStatistics\Mapper\MapperInterface;
use InfluxDB2\Point;

class StatisticsByBoardStatus implements MapperInterface, InfluxDBMapperInterface
{
    use InfluxDBMapperTrait;
    use AggregationItemValidateTrait;

    public function __construct()
    {
        $this->measurement = 'board_task_stats';
    }

    public function mapStatistics(StatisticsInterface $issueStatistics): array
    {
        $points = [];
        foreach ($issueStatistics->getIssueNumbersByColumn() as $item) {
            if (!$this->isValidCountStoryPointItem($item)) {
                continue;
            }

            $value = $item->getValue();
            $points[] = Point::measurement($this->measurement)
                ->addTag('group_name',  $issueStatistics->getIssueGroupName())
                ->addTag('state', $item->getKey())
                ->addField('count', $value[IssueAggregator::AGGREGATION_COUNT])
                ->addField('storypoint', $value[IssueAggregator::AGGREGATION_STORYPOINT])
                ->time(time());
        }
        return $points;
    }
}
