<?php

namespace App\JiraStatistics\Mapper\InfluxDB2\Sprint;

use App\JiraStatistics\StatisticsInterface;
use InfluxDB2\Point;

class StatisticsBySprint extends AbstractSprintStatisticsMapper
{

    public function __construct()
    {
        $this->measurement = 'sprint_stats';
    }

    public function mapStatistics(StatisticsInterface $issueStatistics): array
    {
        $this->checkStatistics($issueStatistics);
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
