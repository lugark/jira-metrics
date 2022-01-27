<?php

namespace App\Tests\JiraStatistics\Aggregator;

use App\JiraStatistics\Aggregator\IssueAggregator;
use App\JiraStatistics\Aggregator\IssueCountAggregator;
use App\JiraStatistics\IssueInformation;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class IssueAggregatorTest extends TestCase
{
    /** @var IssueCountAggregator */
    private $sut;

    public function setUp(): void
    {
        parent::setUp();
        $this->sut = new IssueAggregator();
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

    public function testIssueByType()
    {
        $data = $this->generateIssueInformation();
        $result = $this->sut->execute('byType', $data);
        Assert::assertEquals(2, count($result));
        Assert::assertEquals(
            [IssueAggregator::AGGREGATION_COUNT => 3, IssueAggregator::AGGREGATION_STORYPOINT => 3.0],
            $result->getByKey('Bug')->getValue()
        );
        Assert::assertEquals(
            [IssueAggregator::AGGREGATION_COUNT => 1, IssueAggregator::AGGREGATION_STORYPOINT => 3.0],
            $result->getByKey('Story')->getValue()
        );
    }

    public function testIssueByState()
    {
        $data = $this->generateIssueInformation();
        $result = $this->sut->execute('byState', $data);
        Assert::assertEquals(3, count($result));
        Assert::assertEquals(
            [IssueAggregator::AGGREGATION_COUNT => 1, IssueAggregator::AGGREGATION_STORYPOINT => 3.0],
            $result->getByKey('Doing')->getValue()
        );
        Assert::assertEquals(
            [IssueAggregator::AGGREGATION_COUNT => 2, IssueAggregator::AGGREGATION_STORYPOINT => 3.0],
            $result->getByKey('Done')->getValue()
        );
        Assert::assertEquals(
            [IssueAggregator::AGGREGATION_COUNT => 1, IssueAggregator::AGGREGATION_STORYPOINT => 0.0],
            $result->getByKey('ToDo')->getValue()
        );
    }
}
