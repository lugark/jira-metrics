<?php

namespace App\Tests\Jira\Board;

use App\Jira\Board\Configuration;
use PHPUnit\Framework\TestCase;

class ConfigurationTest extends TestCase
{
    /** JsonMapper */
    public $jsonMapper;

    public function setUp()
    {
        parent::setUp();
        $this->jsonMapper = new \JsonMapper();
    }

    /**
     * @covers \App\Jira\Board\Configuration
     * @covers \App\Jira\Board\Configuration\ColumnConfig
     * @covers \App\Jira\Board\Configuration\ColumnConfig\Column
     * @covers \App\Jira\Board\Configuration\ColumnConfig\MappingStatus
     */
    public function testConfigMapping()
    {
        $json = file_get_contents(realpath(__DIR__ . '/fixtures/responseConfig.json'));
        /** @var Configuration $configuration */
        $configuration = $this->jsonMapper->map(
            json_decode($json, false, 512, JSON_THROW_ON_ERROR),
            new Configuration()
        );

        $configSerializable = $configuration->jsonSerialize();
        $this->assertEquals('scrum', $configSerializable['type']);
        $this->assertInstanceOf(Configuration\ColumnConfig::class, $configSerializable['columnConfig']);

        $columnConfigSerializable = $configSerializable['columnConfig']->jsonSerialize();
        $this->assertEquals(5, count($columnConfigSerializable['columns']));
        $this->assertInstanceOf(Configuration\ColumnConfig\Column::class, $columnConfigSerializable['columns'][0]);

        $columnSerializable = $columnConfigSerializable['columns'][0]->jsonSerialize();
        $this->assertEquals('To Do', $columnSerializable['name']);
        $this->assertEquals(3, count($columnSerializable['statuses']));
        $this->assertInstanceOf(Configuration\ColumnConfig\MappingStatus::class, $columnSerializable['statuses'][0]);

        $mappingSerializable = $columnSerializable['statuses'][0]->jsonSerialize();
        $this->assertEquals($mappingSerializable['id'], 1122);
    }

}
