<?php

namespace App\JiraStatistics\Mapper\InfluxDB2\Sprint;

use App\JiraStatistics\IssueStatisticsInterface;
use App\JiraStatistics\Mapper\InfluxDB\InfluxDBMapperInterface;
use App\JiraStatistics\Mapper\InfluxDB\InfluxDBMapperTrait;
use App\JiraStatistics\Mapper\MapperInterface;
use InfluxDB\Point;

class StatisticsBySprint implements MapperInterface, InfluxDBMapperInterface
{
    use InfluxDBMapperTrait;

    public function __construct()
    {
        $this->measurement = 'sprint_stats';
    }

    public function mapStatistics(IssueStatisticsInterface $sprintStatistics): array
    {
        return[];
        /** TODO - new  mapping */
        return [new Point(
            $this->measurement,
            null,
            ['sprint-name' => $sprintStatistics->getSprintName()],
            [
                'sprint-goal' => $sprintStatistics->getSprintGoal(),
                #TODO: add more numbers - total tasks - done / not done - number types of tasks
                'tasks-start' => 0,
                'tasks-end' => 0,
            ],
            $sprintStatistics->getSprintStart()->getTimestamp()
        )];
    }
}
