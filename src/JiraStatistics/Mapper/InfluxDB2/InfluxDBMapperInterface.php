<?php
namespace App\JiraStatistics\Mapper\InfluxDB2;

interface InfluxDBMapperInterface
{
    public function setMeasurement(string $measurement): void;
}
