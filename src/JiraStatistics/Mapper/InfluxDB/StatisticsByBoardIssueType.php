<?php

namespace App\JiraStatistics\Mapper\InfluxDB;

use App\JiraStatistics\IssueStatisticsInterface;
use App\JiraStatistics\Mapper\MapperInterface;
use InfluxDB\Point;

class StatisticsByBoardIssueType implements MapperInterface, InfluxDBMapperInterface
{
    use InfluxDBMapperTrait;

    public function __construct()
    {
        $this->measurement = 'board_issue_type_stats';
    }

    public function mapStatistics(IssueStatisticsInterface $issueStatistics): array
    {
        $points = [];
        foreach ($issueStatistics->getIssueCountsByType() as $type => $count) {
            $points[] = new Point(
                $this->measurement,
                $count,
                [
                    'group_name' => $issueStatistics->getIssueGroupName(),
                    'issue_type' => $type
                ],
                [],
                strtotime('monday this week')
            );
        }

        return $points;
    }
}
