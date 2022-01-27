<?php

namespace App\JiraStatistics\Mapper\InfluxDB2;

use App\JiraStatistics\Aggregator\IssueCountAggregator;
use App\JiraStatistics\StatisticsInterface;
use App\JiraStatistics\Mapper\MapperInterface;
use InfluxDB2\Point;

class StatisticsByBoardStatus implements MapperInterface, InfluxDBMapperInterface
{
    use InfluxDBMapperTrait;

    public function __construct()
    {
        $this->measurement = 'board_task_stats';
    }

    public function mapStatistics(StatisticsInterface $issueStatistics): array
    {
        $points = [];
        $aggregationResult = $issueStatistics->aggregate('IssueCount', IssueCountAggregator::COUNT_BY_STATE);
        foreach ($aggregationResult as $item) {
            $points[] = Point::measurement($this->measurement)
                ->addTag('group_name',  $issueStatistics->getIssueGroupName())
                ->addTag('state', $item->getKey())
                ->addField('value', $item->getValue())
                ->time(time());
        }
        return $points;
    }
}
