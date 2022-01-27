<?php

namespace App\JiraStatistics\Mapper\InfluxDB\Sprint;

use App\JiraStatistics\StatisticsInterface;
use App\JiraStatistics\Mapper\InfluxDB\InfluxDBMapperInterface;
use App\JiraStatistics\Mapper\InfluxDB\InfluxDBMapperTrait;
use App\JiraStatistics\Mapper\MapperInterface;
use InfluxDB\Point;

class StatisticsBySprintIssueType implements MapperInterface, InfluxDBMapperInterface
{
    use InfluxDBMapperTrait;

    public function __construct()
    {
        $this->measurement = 'sprint_issue_stats';
    }

    public function mapStatistics(StatisticsInterface $issueStatistics): array
    {
        $points = [];
        foreach ($issueStatistics->getIssueCountsByType() as $type => $count) {
            $points[] = new Point(
                $this->measurement,
                $count,
                [
                    'sprint-name' => $issueStatistics->getSprintName(),
                    'task-type' => $type
                ],
                [],
                $issueStatistics->getSprintStart()->getTimestamp()
            );
        }

        return $points;
    }
}
