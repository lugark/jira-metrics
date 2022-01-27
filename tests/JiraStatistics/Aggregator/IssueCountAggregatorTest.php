<?php

namespace App\Tests\JiraStatistics\Aggregator;

use App\JiraStatistics\Aggregator\IssueCountAggregator;
use App\JiraStatistics\IssueInformation;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class IssueCountAggregatorTest extends TestCase
{
    /** @var IssueCountAggregator */
    private $sut;

    public function setUp(): void
    {
        parent::setUp();
        $this->sut = new IssueCountAggregator();
    }

    public function tearDown(): void
    {
        $this->sut = null;
        parent::tearDown();
    }

    private function generateIssueInformation()
    {
        $data[] = new IssueInformation('TestTask1', 'Bug', 'ToDo', 1, '1-1-1970', 0);
        $data[] = new IssueInformation('TestTask2', 'Bug', 'Done', 2, '1-1-1970', 1.0);
        $data[] = new IssueInformation('TestTask3', 'Bug', 'Done', 2, '1-1-1970', 2.0);
        $data[] = new IssueInformation('TestTask4', 'Story', 'Doing', 3, '1-1-1970', 3.0);
        return $data;
    }

    public function testIssueCountByType()
    {
        $data = $this->generateIssueInformation();
        $result = $this->sut->execute('byType', $data);
        Assert::assertEquals(2, count($result));
        Assert::assertEquals(3, $result->getByKey('Bug')->getValue());
        Assert::assertEquals(1, $result->getByKey('Story')->getValue());
    }

    public function testIssueCountByState()
    {
        $data = $this->generateIssueInformation();
        $result = $this->sut->execute('byState', $data);
        Assert::assertEquals(3, count($result));
        Assert::assertEquals(1, $result->getByKey('ToDo')->getValue());
        Assert::assertEquals(1, $result->getByKey('Doing')->getValue());
        Assert::assertEquals(2, $result->getByKey('Done')->getValue());
    }

}
