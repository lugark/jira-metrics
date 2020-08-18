<?php

namespace App\JiraStatistics\Writer;

use InfluxDB\Client;
use InfluxDB\Database;

class InfluxDBWriter implements WriterInterface
{
    const DEFAULT_PRECISION = Database::PRECISION_SECONDS;

    /** @var Client */
    private $client;

    /** @var Database */
    private $db;

    public function __construct()
    {
        $this->influxClient = new Client('localhost');
        $this->db = $this->influxClient->selectDB('jira-metrics');
    }

    public function writeData(array $statistics)
    {
        $this->db->writePoints($statistics, self::DEFAULT_PRECISION);
    }
}