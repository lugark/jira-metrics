<?php

namespace App\JiraStatistics\Writer;

use App\JiraStatistics\IssueStatisticsInterface;
use App\JiraStatistics\Mapper\MapperAwareInterface;
use App\JiraStatistics\Mapper\MapperAwareTrait;
use InfluxDB\Client;
use InfluxDB\Database;

class InfluxDBWriter implements WriterInterface, MapperAwareInterface
{
    use MapperAwareTrait;

    final const DEFAULT_PRECISION = Database::PRECISION_SECONDS;

    private ?\InfluxDB\Database $db = null;

    private ?string $dbName = null;

    public function __construct(private readonly Client $influxClient)
    {
    }

    public function writeData(IssueStatisticsInterface $statistics)
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
