<?php

namespace App\JiraStatistics\Mapper\InfluxDB;

use App\JiraStatistics\Mapper\MapperInterface;
use App\JiraStatistics\SprintStatistics;
use InfluxDB\Point;

class StatisticsByIssueStatus implements MapperInterface, InfluxDBMapperInterface
{
    use InfluxDBMapperTrait;

    public function __construct()
    {
        $this->measurement = 'sprint_state_stats';
    }

    public function mapStatistics(SprintStatistics $sprintStatistics): array
    {
        $points = [];
        foreach ($sprintStatistics->getIssueCountsByState() as $statusName => $count) {
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