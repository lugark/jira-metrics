<?php

namespace App\Tests\Service;

use App\Jira\Board\Configuration;
use App\Service\BoardConfigurationService;
use App\Service\IssueAggregation;
use JiraRestApi\Board\BoardService;
use JiraRestApi\Issue\Issue;
use JiraRestApi\Issue\IssueField;
use JiraRestApi\Issue\IssueType;
use JiraRestApi\Sprint\Sprint;
use JiraRestApi\Sprint\SprintService;
use JiraRestApi\Status\Status;
use PHPUnit\Framework\TestCase;
use function DeepCopy\deep_copy;

class IssueAggregationTest extends TestCase
{

    private SprintService $sprintServiceMock;
    private BoardService  $boardServiceMock;
    private BoardConfigurationService $boardConfigurationServiceMock;
    private IssueAggregation $sut;

    public function setUp()
    {
        parent::setUp();
        $this->sprintServiceMock = $this->getMockBuilder(SprintService::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->boardServiceMock = $this->getMockBuilder(BoardService::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->boardConfigurationServiceMock = $this->getMockBuilder(BoardConfigurationService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->sut = new IssueAggregation($this->sprintServiceMock, $this->boardServiceMock, $this->boardConfigurationServiceMock);
    }

    /**
     * @dataProvider getTicketStatsData
     */
    public function testGetBoardTicketStatistics($issues, $expected)
    {
        $this->boardServiceMock
            ->method('getBoardIssues')
            ->willReturn($issues);

        $stats = $this->sut->getBoardTicketStatistics(new Configuration(), []);
        $this->assertEquals($expected, $stats->getIssueCountsByType());
    }

    public function getTicketStatsData()
    {
        $issue1 = new Issue();
        $issue1->fields = new IssueField();
        $issue1->fields->status = new Status();
        $issue1->fields->issuetype = new IssueType();
        $issue1->fields->issuetype->name = 'Task';
        $issue1->fields->status->name = 'MyStatus';
        $issue1->fields->status->id = 123;
        $issue1->fields->created = time();

        $issue2 = deep_copy($issue1);
        $issue2->fields->issuetype->subtask=true;
        $issue2->fields->issuetype->name = 'SubTask';

        $issues1 = new \ArrayObject();
        $issues1->append($issue1);
        $issues2 = new \ArrayObject();
        $issues2->append($issue1);
        $issues2->append($issue2);

        return [
            'oneTest' => [
                $issues1,
                ['Task' => 1]
            ],
            'twoTest' => [
                $issues2,
                ['Task' => 1]
            ],
        ];
    }

    /**
     * @dataProvider getTicketStatsData
     */
    public function testGetSprintTicketStatistics($issues, $expected)
    {
        $this->sprintServiceMock
            ->method('getSprintIssues')
            ->willReturn($issues);

        $sprint = new Sprint();
        $sprint->id = 123;
        $sprint->originBoardId = 456;

        $stats = $this->sut->getSprintTicketStatistics($sprint, []);
        $this->assertEquals($expected, $stats->getIssueCountsByType());

    }
}
