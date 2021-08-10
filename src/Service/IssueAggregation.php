<?php

namespace App\Service;

use App\Jira\Board\Configuration;
use App\JiraStatistics\BoardStatistics;
use App\JiraStatistics\IssueStatistic;
use App\JiraStatistics\AbstractIssueStatistics;
use App\JiraStatistics\SprintStatistics;
use JiraRestApi\Board\BoardService;
use JiraRestApi\Issue\Issue;
use JiraRestApi\Sprint\Sprint;
use JiraRestApi\Sprint\SprintService;

class IssueAggregation
{
    protected SprintService $sprintService;

    protected BoardService $boardService;

    protected BoardConfigurationService $boardConfigService;

    public function __construct(SprintService $sprintService, BoardService $boardService, BoardConfigurationService $boardConfigurationService)
    {
        $this->sprintService = $sprintService;
        $this->boardConfigService = $boardConfigurationService;
        $this->boardService = $boardService;
    }

    /**
     * @deprecated
     * @codeCoverageIgnore
     */
    public function getTicketStatisticsBySprint(int $sprintId, array $queryParams, bool $subtaskInTypeStats=false)
    {
        $queryOptions = array_merge(
            [
            'fields' => urlencode('status,issuetype,parent')
            ],
            $queryParams
        );

        $statusStats = [];
        $typeStats = [];
        /** @var Issue $issue */
        foreach ($this->sprintService->getSprintIssues($sprintId, $queryOptions) as $issue) {
            $statusName = $issue->fields->status->name;
            $statusStats[$statusName] = array_key_exists($statusName, $statusStats)
                ? $statusStats[$statusName] + 1
                : 1;

            if (!$issue->fields->issuetype->subtask || ($issue->fields->issuetype->subtask && $subtaskInTypeStats)) {
                $typeName = is_null($issue->fields->parent)
                    ? $issue->fields->issuetype->name
                    : $this->getParentTaskType($issue);
                $typeStats[$typeName] = array_key_exists($typeName, $typeStats)
                    ? $typeStats[$typeName] + 1
                    : 1;
            }
        }

        return ['status' => $statusStats, 'type' => $typeStats];
    }

    /**
     * @deprecated
     * @codeCoverageIgnore
     */
    private function getParentTaskType(Issue $issue): ?string
    {
        if (is_null($issue->fields->parent)) {
            return '';
        }

        /** @var Issue $parent */
        $parent = $issue->fields->parent;
        return $parent->fields->issuetype->name;
    }

    public function getSprintTicketStatistics(
        Sprint $sprint,
        array $queryParams,
        bool $subtaskInTypeStats=false
    ): SprintStatistics {
        $queryOptions = array_merge(
            [
                'fields' => urlencode('status,issuetype,parent'),
                'maxResults' => 100
            ],
            $queryParams
        );

        $boardColumnMapping = $this->boardConfigService->fetchBoardColumnMapping($sprint->originBoardId);

        /** @var SprintStatistics $issueStats */
        $issueStats = new SprintStatistics($sprint, $boardColumnMapping);

        $this->addStatistics(
            $issueStats,
            $this->sprintService->getSprintIssues($sprint->id, $queryOptions),
            $subtaskInTypeStats
        );

        return $issueStats;
    }

    private function addStatistics(AbstractIssueStatistics &$issueStatistics, $issues, $subtaskInTypeStats)
    {
        foreach ($issues as $issue) {
            if ($issue->fields->issuetype->subtask && !$subtaskInTypeStats) {
                continue;
            }
            $issueStatistics->addIssueStatistic(
                new IssueStatistic(
                    $issue->id,
                    $issue->fields->issuetype->name,
                    $issue->fields->status->name,
                    $issue->fields->status->id,
                    $issue->fields->created
                )
            );
        }
    }

    public function getBoardTicketStatistics(
        Configuration $boardConfig,
        array $queryParams,
        bool $subtaskInTypeStats=false
    ): AbstractIssueStatistics
    {
        $queryOptions = array_merge(
            [
                'fields' => urlencode('status,issuetype,parent'),
            ],
            $queryParams
        );

        $boardColumnMapping = $this->boardConfigService->getBoardColumnMapping($boardConfig);

        /** @var AbstractIssueStatistics $issueStats */
        $issueStats = new BoardStatistics($boardConfig, $boardColumnMapping);

        $this->addStatistics(
            $issueStats,
            $this->boardService->getBoardIssues($boardConfig->id, $queryOptions),
            $subtaskInTypeStats
        );
        return $issueStats;
    }
}
