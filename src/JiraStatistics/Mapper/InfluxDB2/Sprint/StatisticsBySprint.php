<?php

namespace App\JiraStatistics\Mapper\InfluxDB2\Sprint;

use App\JiraStatistics\IssueStatisticsInterface;
use App\JiraStatistics\Mapper\InfluxDB\InfluxDBMapperInterface;
use App\JiraStatistics\Mapper\InfluxDB\InfluxDBMapperTrait;
use App\JiraStatistics\Mapper\MapperInterface;
use InfluxDB2\Point;

class StatisticsBySprint implements MapperInterface, InfluxDBMapperInterface
{
    use InfluxDBMapperTrait;

    public function __construct()
    {
        $this->measurement = 'sprint_stats';
    }

    public function mapStatistics(IssueStatisticsInterface $issueStatistics): array
    {
        return [
            Point::measurement($this->measurement)
                ->addTag('sprint-name', $issueStatistics->getSprintName())
                ->addField('sprint-goal', $issueStatistics->getSprintGoal())
                ->addField('task-start', 0)
                ->addField('task-end', 0)
                ->time($issueStatistics->getSprintStart()->getTimestamp())
            ];

    }
}
