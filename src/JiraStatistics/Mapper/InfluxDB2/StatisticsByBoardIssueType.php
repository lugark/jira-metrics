<?php

namespace App\JiraStatistics\Mapper\InfluxDB2;

use App\JiraStatistics\Aggregator\IssueCountAggregator;
use App\JiraStatistics\IssueStatisticsInterface;
use App\JiraStatistics\Mapper\MapperInterface;
use App\JiraStatistics\StatisticsInterface;
use InfluxDB2\Point;

class StatisticsByBoardIssueType implements MapperInterface, InfluxDBMapperInterface
{
    use InfluxDBMapperTrait;

    public function __construct()
    {
        $this->measurement = 'board_issue_type_stats';
    }

    public function mapStatistics(StatisticsInterface $issueStatistics): array
    {
        $points = [];
        $aggregationResult = $issueStatistics->aggregate('IssueCount', IssueCountAggregator::COUNT_BY_TYPE);
        foreach ($aggregationResult as $item) {
            $points[] = Point::measurement($this->measurement)
                ->addTag('group_name',  $issueStatistics->getIssueGroupName())
                ->addTag('issue_type', $item->getKey())
                ->addField('value', $item->getValue())
                ->time(strtotime('monday this week'));
        }

        return $points;
    }
}
