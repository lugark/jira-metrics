<?php

namespace App\JiraStatistics\Writer;

use App\JiraStatistics\IssueStatisticsInterface;
use App\JiraStatistics\Mapper\MapperAwareInterface;
use App\JiraStatistics\Mapper\MapperAwareTrait;
use InfluxDB2\Client;
use InfluxDB2\Model\WritePrecision;
use InfluxDB2\WriteApi;
use InfluxDB2\WriteType;

class InfluxDB2Writer implements WriterInterface, MapperAwareInterface
{
    use MapperAwareTrait;

    const DEFAULT_PRECISION = WritePrecision::S;

    private Client $influxClient;

    private WriteApi $writeApi;

    private string $bucket;

    private string $orga;

    public function __construct(Client $client)
    {
        var_dump($client->options);
        $this->influxClient = $client;
    }

    public function writeData(IssueStatisticsInterface $statistics)
    {
        $mappedData = [];
        foreach ($this->mapper as $mapper) {
            $this->getWriteApi()->write(
                $mapper->mapStatistics($statistics),
                self::DEFAULT_PRECISION,
                $this->bucket,
                $this->orga
            );
        }
    }

    public function setBucket(string $bucket): void
    {
        $this->bucket = $bucket;
    }

    public function setOrga(string $orga): void
    {
        $this->orga = $orga;
    }

    private function getWriteApi(): WriteApi
    {
        if (empty($this->writeApi)){
            $this->writeApi = $this->influxClient->createWriteApi();
        }

        return $this->writeApi;
    }
}
