<?php

namespace App\JiraStatistics\Mapper\InfluxDB2;

trait InfluxDBMapperTrait
{
    protected $measurement;

    public function setMeasurement(string $measurement): void
    {
        $this->measurement = $measurement;
    }
}
