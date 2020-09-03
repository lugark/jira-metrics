<?php

namespace App\Service;

use App\JiraStatistics\IssueStatistic;
use App\JiraStatistics\SprintStatistics;
use JiraRestApi\Issue\Issue;
use JiraRestApi\Sprint\Sprint;
use JiraRestApi\Sprint\SprintService;

class IssueAggregation
{
    /** @var SprintService  */
    protected $sprintService;

    public function __construct(SprintService $sprintService, BoardConfigurationService $boardConfigurationService)
    {
        $this->sprintService = $sprintService;
        $this->boardConfigService = $boardConfigurationService;
    }

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

    private function getParentTaskType(Issue $issue): ?string
    {
        if (is_null($issue->fields->parent)) {
            return '';
        }

        /** @var Issue $parent */
        $parent = $issue->fields->parent;
        return $parent->fields->issuetype->name;
    }

    private function getBoardColumnMapping(int $boardId)
    {
        $config = $this->boardConfigService->getBoardConfig($boardId);
        if (!empty(($config))) {
            return $config->columnConfig;
        }
        return [];
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

        $boardColumnMapping = $this->boardConfigService->getBoardColumnMapping($sprint->originBoardId);

        /** @var SprintStatistics $issueStats */
        $issueStats = new SprintStatistics($sprint, $boardColumnMapping);

        /** @var Issue $issue */
        foreach ($this->sprintService->getSprintIssues($sprint->id, $queryOptions) as $issue) {
            if ($issue->fields->issuetype->subtask && !$subtaskInTypeStats) {
                continue;
            }
            $issueStats->addIssueStatistic(
                new IssueStatistic(
                    $issue->id,
                    $issue->fields->issuetype->name,
                    $issue->fields->status->name,
                    $issue->fields->status->id,
                    $issue->fields->created
                )
            );
        }

        return $issueStats;
    }
}