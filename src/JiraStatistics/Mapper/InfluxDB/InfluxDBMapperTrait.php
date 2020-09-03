<?php

namespace App\JiraStatistics\Mapper\InfluxDB;

trait InfluxDBMapperTrait
{
    protected $measurement;

    public function setMeasurement(string $measurement): void
    {
        $this->measurement = $measurement;
    }
}