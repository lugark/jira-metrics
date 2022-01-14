<?php

namespace App\JiraStatistics\Mapper\InfluxDB2\Sprint;

use App\JiraStatistics\IssueStatisticsInterface;
use App\JiraStatistics\Mapper\InfluxDB\InfluxDBMapperInterface;
use App\JiraStatistics\Mapper\InfluxDB\InfluxDBMapperTrait;
use App\JiraStatistics\Mapper\MapperInterface;
use InfluxDB2\Point;

class StatisticsBySprintIssueType implements MapperInterface, InfluxDBMapperInterface
{
    use InfluxDBMapperTrait;

    public function __construct()
    {
        $this->measurement = 'sprint_issue_stats';
    }

    public function mapStatistics(IssueStatisticsInterface $issueStatistics): array
    {
        $points = [];
        foreach ($issueStatistics->getIssueCountsByType() as $type => $count) {
            $points[] = Point::measurement($this->measurement)
                ->addTag('sprint-name' , $issueStatistics->getSprintName())
                ->addTag('task-type', $type)
                ->addField('value', $count)
                ->time($issueStatistics->getSprintStart()->getTimestamp());
        }

        return $points;
    }
}
