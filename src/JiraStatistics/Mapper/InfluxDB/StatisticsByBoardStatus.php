<?php

namespace App\JiraStatistics\Mapper\InfluxDB;

use App\JiraStatistics\StatisticsInterface;
use App\JiraStatistics\Mapper\MapperInterface;
use InfluxDB\Point;

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
        foreach ($issueStatistics->getCountsByBoardColumns() as $state => $count) {
            $points[] = new Point(
                $this->measurement,
                $count,
                [
                    'group_name' => $issueStatistics->getIssueGroupName(),
                    'state' => $state
                ],
                [],
                time()
            );
        }
        return $points;
    }
}
