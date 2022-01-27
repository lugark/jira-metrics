<?php

namespace App\JiraStatistics\Mapper;

use App\JiraStatistics\StatisticsInterface;

interface MapperInterface
{
    public function mapStatistics(StatisticsInterface $issueStatistics): array;
}
