<?php

namespace App\JiraStatistics;

interface IssueStatisticsInterface
{
    public function addIssueStatistic(IssueStatistic $issueStatistic);
    public function getIssueCountsByType(): array;
    public function getIssueCountsByState(): array;
    public function getIssueCountByTypeAndState(): array;
    public function getCountsByBoardColumns(): array;
    public function getIssueGroupName(): string;
    public function getIssueGroupId(): int;
}
