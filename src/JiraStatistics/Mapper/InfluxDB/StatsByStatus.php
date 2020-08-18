<?php

namespace App\JiraStatistics\Mapper\InfluxDB;

use App\JiraStatistics\SprintStatistics;
use InfluxDB\Point;

class StatsByStatus implements \App\JiraStatistics\Mapper\MapperInterface
{
    const MEASUREMENT_TASK_STATUS = 'sprint_task_stats';

    public function mapStatistics(SprintStatistics $sprintStatistics): array
    {
        $points = [];
        foreach ($sprintStatistics->getIssueCountsByState() as $statusName => $count) {
            $points[] = new Point(
                'task_states',
                $count,
                ['status-name' => $statusName],
                [],
                time()
            );

        }

        $points[] = new Point(
            self::MEASUREMENT_TASK_STATUS,
            null,
            ['sprint' => $sprintStatistics->getSprintName()],
            $sprintStatistics->getIssueCountsByState(),
            strtotime('today')
        );

        return $points;
    }
}