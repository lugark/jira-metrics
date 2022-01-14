<?php

namespace App\JiraStatistics\Mapper\InfluxDB2;

use App\JiraStatistics\IssueStatisticsInterface;
use App\JiraStatistics\Mapper\MapperInterface;
use InfluxDB\Point;


class StatisticsByIssueStatus implements MapperInterface, InfluxDBMapperInterface
{
    use InfluxDBMapperTrait;

    public function __construct()
    {
        $this->measurement = 'sprint_state_stats';
    }

    public function mapStatistics(IssueStatisticsInterface $issueStatistics): array
    {
        return [];
        /** TODO - new  mapping */

        $points = [];
        foreach ($issueStatistics->getIssueCountsByState() as $statusName => $count) {
            $points[] = new Point(
                $this->measurement,
                $count,
                ['status-name' => $statusName],
                [],
                time()
            );
        }

        return $points;
    }
}
