<?php

namespace App\JiraStatistics\Mapper\InfluxDB;

use App\JiraStatistics\SprintStatistics;
use InfluxDB\Point;

class StatsBySprint implements \App\JiraStatistics\Mapper\MapperInterface
{
    const MEASUREMENT_SPRINT_STATS = 'sprint_stats';

    public function mapStatistics(SprintStatistics $sprintStatistics): array
    {
        return [new Point(
            self::MEASUREMENT_SPRINT_STATS,
            null,
            ['sprint-name' => $sprintStatistics->getSprintName()],
            [
                'sprint-goal' => $sprintStatistics->getSprintGoal(),
                'tasks-start' => 0,
                'tasks-end' => 0,
            ],
            $sprintStatistics->getSprintStart()->getTimestamp()
        )];
    }
}