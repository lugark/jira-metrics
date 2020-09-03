<?php

namespace App\JiraStatistics\Mapper\InfluxDB;

use App\JiraStatistics\Mapper\MapperInterface;
use App\JiraStatistics\SprintStatistics;
use InfluxDB\Point;

class StatsByBoardStatus implements MapperInterface, InfluxDBMapperInterface
{
    use InfluxDBMapperTrait;

    public function __construct()
    {
        $this->measurement = 'board_task_stats';
    }

    public function mapStatistics(SprintStatistics $sprintStatistics): array
    {
        $points = [];
        foreach ($sprintStatistics->getCountsByBoardColumns() as $state => $count) {
            $points[] = new Point(
                $this->measurement,
                $count,
                [
                    'sprint' => $sprintStatistics->getSprintName(),
                    'state' => $state
                ],
                [],
                time()
            );
        }
        return $points;
    }
}