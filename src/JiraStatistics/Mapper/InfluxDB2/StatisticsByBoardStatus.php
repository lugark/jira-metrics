<?php

namespace App\JiraStatistics\Mapper\InfluxDB2;

use App\JiraStatistics\IssueStatisticsInterface;
use App\JiraStatistics\Mapper\MapperInterface;
use InfluxDB2\Point;

class StatisticsByBoardStatus implements MapperInterface, InfluxDBMapperInterface
{
    use InfluxDBMapperTrait;

    public function __construct()
    {
        $this->measurement = 'board_task_stats';
    }

    public function mapStatistics(IssueStatisticsInterface $issueStatistics): array
    {
        $points = [];
        foreach ($issueStatistics->getCountsByBoardColumns() as $state => $count) {
            $points[] = Point::measurement($this->measurement)
                ->addTag('group_name',  $issueStatistics->getIssueGroupName())
                ->addTag('state', $state)
                ->addField('value', $count)
                ->time(time());
        }
        return $points;
    }
}
