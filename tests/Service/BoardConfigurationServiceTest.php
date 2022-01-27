<?php
namespace App\Tests\Service;

use App\Jira\Board\Configuration;
use App\Jira\Board\Configuration\ColumnConfig;
use App\Jira\Board\Configuration\ColumnConfig\Column;
use App\Jira\Board\Configuration\ColumnConfig\MappingStatus;
use App\Service\BoardConfigurationService;
use JiraRestApi\Configuration\ConfigurationInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class BoardConfigurationServiceTest extends TestCase
{

    public function testGetBoardColumnMapping()
    {
        $loggerMock = $this->getMockBuilder(LoggerInterface::class)->getMock();
        $configMock = $this->getMockBuilder(ConfigurationInterface::class)->getMock();

        $config = new Configuration();
        $config->columnConfig = new ColumnConfig();

        $sut = new BoardConfigurationService($configMock, $loggerMock);
        $mapping = $sut->getBoardColumnMapping($config);
        $this->assertEmpty($mapping);

        $column = new Column();
        $column->name = 'TestCol';
        $column->statuses[] = new MappingStatus();
        $column->statuses[0]->id = 99;
        $config->columnConfig->columns[] = $column;
        $mapping = $sut->getBoardColumnMapping($config);
    }
}
