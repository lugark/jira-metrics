<?php

namespace App\JiraStatistics\Mapper\InfluxDB2;

use App\JiraStatistics\StatisticsInterface;
use App\JiraStatistics\Mapper\MapperInterface;
use InfluxDB2\Point;

class StatisticsByBoardStatusDaily implements MapperInterface, InfluxDBMapperInterface
{
    use InfluxDBMapperTrait;

    public function __construct()
    {
        $this->measurement = 'board_task_stats_daily';
    }

    public function mapStatistics(StatisticsInterface $issueStatistics): array
    {
        $points = [];
        foreach ($issueStatistics->getIssueCountByColumn() as $item) {
            $points[] = Point::measurement($this->measurement)
                ->addTag('group_name',  $issueStatistics->getIssueGroupName())
                ->addTag('state', $item->getKey())
                ->addField('value', $item->getValue())
                ->time(strtotime('today'));
        }
        return $points;
    }
}
