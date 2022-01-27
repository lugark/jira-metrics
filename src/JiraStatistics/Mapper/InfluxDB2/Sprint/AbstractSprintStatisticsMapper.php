<?php

namespace App\JiraStatistics\Mapper\InfluxDB2\Sprint;

use App\JiraStatistics\StatisticsInterface;
use App\JiraStatistics\Mapper\InfluxDB\InfluxDBMapperTrait;
use App\JiraStatistics\Mapper\InfluxDB2\InfluxDBMapperInterface;
use App\JiraStatistics\Mapper\MapperException;
use App\JiraStatistics\Mapper\MapperInterface;
use App\JiraStatistics\SprintStatistics;

abstract class AbstractSprintStatisticsMapper implements MapperInterface, InfluxDBMapperInterface
{
    use InfluxDBMapperTrait;

    protected function checkStatistics(StatisticsInterface $issueStatistics)
    {
        if (!($issueStatistics instanceof SprintStatistics)) {
            throw new MapperException('Mapper ' . self::class . ' requires statistics of type SprintStatistics');
        }
    }

}
