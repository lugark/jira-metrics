<?php
namespace App\JiraStatistics\Mapper\InfluxDB;

interface InfluxDBMapperInterface
{
    public function setMeasurement(string $measurement): void;
}