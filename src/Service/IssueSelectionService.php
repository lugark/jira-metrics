<?php

namespace App\Service;

use App\Jira\Board\Configuration;
use App\JiraStatistics\BoardStatistics;
use App\JiraStatistics\IssueInformation;
use App\JiraStatistics\AbstractStatistics;
use App\JiraStatistics\SprintStatistics;
use JiraRestApi\Board\BoardService;
use JiraRestApi\Issue\Issue;
use JiraRestApi\Sprint\Sprint;
use JiraRestApi\Sprint\SprintService;

class IssueSelectionService
{
    const DEFAULT_FIELDS_TO_FETCH='status,issuetype,parent';
    const DEFAULT_MAX_RESULT=100;

    protected SprintService $sprintService;

    protected BoardService $boardService;

    public function __construct(SprintService $sprintService, BoardService $boardService, BoardConfigurationService $boardConfigurationService)
    {
        $this->sprintService = $sprintService;
        $this->boardConfigService = $boardConfigurationService;
        $this->boardService = $boardService;
    }

    public function getTicketStatisticsBySprint(int $sprintId, array $queryParams, bool $subtaskInTypeStats=false)
    {
        $queryOptions = array_merge(
            [
            'fields' => urlencode(self::DEFAULT_FIELDS_TO_FETCH)
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

    public function getSprintTicketStatistics(
        Sprint $sprint,
        array $queryParams,
        bool $subtaskInTypeStats=false
    ): SprintStatistics {
        $boardConfig = $this->boardConfigService->getBoardConfig($sprint->originBoardId);
        $estimationField = $boardConfig->estimation->field->fieldId;
        $fieldsToGet = self::DEFAULT_FIELDS_TO_FETCH;
        $fieldsToGet .= $boardConfig->hasEstimation() ? ',' . $estimationField : '';
        $queryOptions = array_merge(
            [
                'fields' => urlencode($fieldsToGet),
                'maxResults' => self::DEFAULT_MAX_RESULT
            ],
            $queryParams
        );

        $boardColumnMapping = $this->boardConfigService->fetchBoardColumnMapping($sprint->originBoardId);

        /** @var SprintStatistics $issueStats */
        $issueStats = new SprintStatistics($sprint, $boardColumnMapping);

        /** @var Issue $issue */
        foreach ($this->sprintService->getSprintIssues($sprint->id, $queryOptions) as $issue) {
            if ($issue->fields->issuetype->subtask && !$subtaskInTypeStats) {
                continue;
            }
            $issueStats->addIssueInformation(
                new IssueInformation(
                    $issue->id,
                    $issue->fields->issuetype->name,
                    $issue->fields->status->name,
                    $issue->fields->status->id,
                    $issue->fields->created,
                    $issue->fields->$estimationField ?? 0
                )
            );
        }

        return $issueStats;
    }

    public function getBoardTicketStatistics(
        Configuration $boardConfig,
        array $queryParams,
        bool $subtaskInTypeStats=false
    ): AbstractStatistics
    {
        $queryOptions = array_merge(
            [
                'fields' => urlencode(self::DEFAULT_FIELDS_TO_FETCH),
            ],
            $queryParams
        );

        $boardColumnMapping = $this->boardConfigService->getBoardColumnMapping($boardConfig);

        /** @var AbstractStatistics $issueStats */
        $issueStats = new BoardStatistics($boardConfig, $boardColumnMapping);

        /** @var Issue $issue */
        foreach ($this->boardService->getBoardIssues($boardConfig->id, $queryOptions) as $issue) {
            if ($issue->fields->issuetype->subtask && !$subtaskInTypeStats) {
                continue;
            }
            $issueStats->addIssueInformation(
                new IssueInformation(
                    $issue->id,
                    $issue->fields->issuetype->name,
                    $issue->fields->status->name,
                    $issue->fields->status->id,
                    $issue->fields->created,
                    0
                )
            );
        }
        return $issueStats;
    }
}
