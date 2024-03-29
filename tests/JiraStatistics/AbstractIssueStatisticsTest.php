<?php

namespace App\Tests\JiraStatistics;

use App\Jira\Board\Configuration;
use App\JiraStatistics\AbstractIssueStatistics;
use App\JiraStatistics\BoardStatistics;
use PHPUnit\Framework\TestCase;

class AbastractIssueStatisticsTest extends TestCase
{
    public function setUp(): void
    {
    }

    public function tearDown(): void
    {
    }

    public function testNewInstance()
    {
        $sut = $this->getMockForAbstractClass(AbstractIssueStatistics::class);
        $this->assertEmpty($sut->getIssueCountsByType());
    }

}
