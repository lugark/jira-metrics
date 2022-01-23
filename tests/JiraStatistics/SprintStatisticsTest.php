<?php

namespace App\Tests\JiraStatistics;

use App\JiraStatistics\SprintStatistics;
use JiraRestApi\Sprint\Sprint;
use PHPUnit\Framework\TestCase;

class SprintStatisticsTest extends TestCase
{
    public function testNewInstance()
    {
        $sprint = new Sprint();
        $sprint->setName('TestSprint');
        $sprint->id = 66;

        $sut = new SprintStatistics($sprint);
        $this->assertEquals('TestSprint', $sut->getIssueGroupName());
        $this->assertEquals(66, $sut->getIssueGroupId());
    }
}
