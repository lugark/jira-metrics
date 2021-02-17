<?php

namespace App\Service;

use App\Jira\Board\Configuration;
use App\Jira\Board\Configuration\ColumnConfig\Column\MappingStatus;
use JiraRestApi\AgileApiTrait;
use JiraRestApi\Configuration\ConfigurationInterface;
use JiraRestApi\JiraClient;
use Psr\Log\LoggerInterface;

class BoardConfigurationService extends JiraClient
{
    use AgileApiTrait;

    private $uri = '/board/%d/configuration';

    public function __construct(
        ConfigurationInterface $configuration = null,
        LoggerInterface $logger = null,
        $path = './'
    ) {
        parent::__construct($configuration, $logger, $path);
        $this->setupAPIUri();
    }

    public function getBoardColumnMapping(int $boardId): array
    {
        $config = $this->getBoardConfig($boardId);
        if (empty(($config))) {
            return [];
        }

        $columnMapping = [];
        /** @var Configuration\ColumnConfig\Column $column */
        foreach ($config->columnConfig->columns as $column) {
            $columnMapping[$column->name] = [];
            /** @var MappingStatus $status */
            foreach ($column->statuses as $status) {
                $columnMapping[$column->name][] = $status->id;
            }
        }

        return $columnMapping;
    }

    public function getBoardConfig($boardId): ?Configuration
    {
        $json = $this->exec(sprintf($this->uri, $boardId), null);

        try {
            return $this->json_mapper->map(
                json_decode($json, false, 512, JSON_THROW_ON_ERROR),
                new Configuration()
            );
        } catch (\JsonException $exception) {
            $this->log->error("Response cannot be decoded from json\nException: {$exception->getMessage()}");

            return null;
        }

    }
}