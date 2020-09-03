<?php

namespace App\JiraStatistics\Mapper\InfluxDB;

use App\JiraStatistics\SprintStatistics;
use InfluxDB\Point;

class StatsByStatus implements \App\JiraStatistics\Mapper\MapperInterface
{
    private $measurementTaskStatus = 'task_states';
    private $measurementSprintTaskStatus = 'sprint_task_stats';

    public function mapStatistics(SprintStatistics $sprintStatistics): array
    {
        $points = [];
        foreach ($sprintStatistics->getIssueCountsByState() as $statusName => $count) {
            $points[] = new Point(
                $this->measurementTaskStatus,
                $count,
                ['status-name' => $statusName],
                [],
                time()
            );
        }

        $points[] = new Point(
            $this->measurementSprintTaskStatus,
            null,
            ['sprint' => $sprintStatistics->getSprintName()],
            $sprintStatistics->getIssueCountsByState(),
            strtotime('today')
        );

        return $points;
    }
}