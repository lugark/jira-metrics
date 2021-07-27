<?php

namespace App\JiraStatistics\Mapper;

use App\JiraStatistics\IssueStatisticsInterface;

interface MapperInterface
{
    public function mapStatistics(IssueStatisticsInterface $issueStatistics): array;
}
