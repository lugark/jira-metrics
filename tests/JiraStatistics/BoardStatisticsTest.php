<?php

namespace App\Tests\JiraStatistics;

use App\Jira\Board\Configuration;
use App\JiraStatistics\BoardStatistics;
use PHPUnit\Framework\TestCase;

class BoardStatisticsTest extends TestCase
{
    public function testNewInstance()
    {
        $boardConfig = new Configuration();
        $boardConfig->id = 99;
        $boardConfig->name = 'TestBoard';

        $sut = new BoardStatistics($boardConfig);
        $this->assertEquals('TestBoard', $sut->getIssueGroupName());
        $this->assertEquals(99, $sut->getIssueGroupId());
    }
}
