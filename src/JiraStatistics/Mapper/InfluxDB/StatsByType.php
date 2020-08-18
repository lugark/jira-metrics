<?php

namespace App\JiraStatistics\Mapper\InfluxDB;

use App\JiraStatistics\SprintStatistics;
use InfluxDB\Point;

class StatsByType implements \App\JiraStatistics\Mapper\MapperInterface
{
    const MEASUREMENT_TYPE_STATS = 'sprint_type_stats';

    public function mapStatistics(SprintStatistics $sprintStatistics): array
    {
        $points = [];
        foreach ($sprintStatistics->getIssueCountsByType() as $type => $count) {
            $points[] = new Point(
                self::MEASUREMENT_TYPE_STATS,
                $count,
                [
                    'sprint-name' => $sprintStatistics->getSprintName(),
                    'task-type' => $type
                ],
                [],
                $sprintStatistics->getSprintStart()->getTimestamp()
            );
        }

        foreach ($sprintStatistics->getIssueCountByTypeAndState() as $type => $stat) {
        }
        return $points;
    }
}