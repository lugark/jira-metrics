<?php

namespace App\JiraStatistics\Mapper\InfluxDB;

use App\JiraStatistics\Mapper\MapperInterface;
use App\JiraStatistics\SprintStatistics;
use InfluxDB\Point;

class StatisticsBySprintIssueType implements MapperInterface, InfluxDBMapperInterface
{
    use InfluxDBMapperTrait;

    public function __construct()
    {
        $this->measurement = 'sprint_issue_stats';
    }

    public function mapStatistics(SprintStatistics $sprintStatistics): array
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