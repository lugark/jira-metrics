<?php

namespace App\Tests\JiraStatistics;

use App\Jira\Board\Configuration;
use App\JiraStatistics\AbstractStatistics;
use App\JiraStatistics\BoardStatistics;
use PHPUnit\Framework\TestCase;

class AbastractIssueStatisticsTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    public function testNewInstance()
    {
        $sut = $this->getMockForAbstractClass(AbstractStatistics::class);
        $this->assertEmpty($sut->getIssueCountsByType());
    }

}
