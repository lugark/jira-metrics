<?php

namespace App\JiraStatistics\Mapper\InfluxDB\Sprint;

use App\JiraStatistics\IssueStatisticsInterface;
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

    public function mapStatistics(IssueStatisticsInterface $sprintStatistics): array
    {
        $points = [];
        foreach ($sprintStatistics->getIssueCountsByType() as $type => $count) {
            $points[] = new Point(
                $this->measurement,
                $count,
                [
                    'sprint-name' => $sprintStatistics->getSprintName(),
                    'task-type' => $type
                ],
                [],
                $sprintStatistics->getSprintStart()->getTimestamp()
            );
        }

        return $points;
    }
}
