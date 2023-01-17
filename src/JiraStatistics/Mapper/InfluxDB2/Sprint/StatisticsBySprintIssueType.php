<?php

namespace App\JiraStatistics\Mapper\InfluxDB2\Sprint;

use App\JiraStatistics\StatisticsInterface;
use InfluxDB2\Point;

class StatisticsBySprintIssueType extends AbstractSprintStatisticsMapper
{
    public function __construct()
    {
        $this->measurement = 'sprint_issue_stats';
    }

    public function mapStatistics(StatisticsInterface $issueStatistics): array
    {
        $points = [];
        $this->checkStatistics($issueStatistics);
        foreach ($issueStatistics->getIssueCountsByType() as $type => $count) {
            $points[] = Point::measurement($this->measurement)
                ->addTag('sprint-name' , $issueStatistics->getSprintName())
                ->addTag('issue-type', $type)
                ->addField('count', $count)
                ->time($issueStatistics->getSprintStart()->getTimestamp());
        }

        return $points;
    }
}
