<?php

namespace App\JiraStatistics\Writer;

use App\JiraStatistics\StatisticsInterface;
use App\JiraStatistics\Mapper\MapperAwareInterface;
use App\JiraStatistics\Mapper\MapperAwareTrait;
use InfluxDB\Client;
use InfluxDB\Database;

class InfluxDBWriter implements WriterInterface, MapperAwareInterface
{
    use MapperAwareTrait;

    const DEFAULT_PRECISION = Database::PRECISION_SECONDS;

    /** @var Client */
    private $influxClient;

    /** @var Database */
    private $db;

    /** @var string */
    private $dbName;

    public function __construct(Client $client)
    {
        $this->influxClient = $client;
    }

    public function writeData(StatisticsInterface $statistics)
    {
        $mappedData = [];
        foreach ($this->mapper as $mapper) {
            $mappedData = array_merge($mappedData, $mapper->mapStatistics($statistics));
        }

        $this->db->writePoints($mappedData, self::DEFAULT_PRECISION);
    }

    public function setDbName(string $dbName): void
    {
        $this->dbName = $dbName;
        $this->db = $this->influxClient->selectDB($this->dbName);
    }
}
