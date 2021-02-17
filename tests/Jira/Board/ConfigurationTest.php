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

    public function testConfigMapping()
    {
        $json = file_get_contents(realpath(__DIR__ . '/fixtures/responseConfig.json'));
        /** @var Configuration $configuration */
        $configuration = $this->jsonMapper->map(
            json_decode($json, false, 512, JSON_THROW_ON_ERROR),
            new Configuration()
        );

        $this->assertEquals('scrum', $configuration->type);
        $this->assertEquals(5, count($configuration->columnConfig->columns));
        $this->assertEquals('To Do', $configuration->columnConfig->columns[0]->name);
    }

}
