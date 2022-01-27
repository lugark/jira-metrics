<?php

namespace App\JiraStatistics;

use App\JiraStatistics\Aggregator\AggregationResult;

interface StatisticsInterface
{
    public function addIssueInformation(IssueInformation $issueInformation);
    public function aggregate(string $aggregatorName, string $aggregation): AggregationResult;
    public function getIssueGroupName(): string;
    public function getIssueGroupId(): int;

    public function getIssueCountByColumn(): AggregationResult;

    /** @deprecated  */
    public function getIssueCountsByType(): array;
    /** @deprecated  */
    public function getIssueCountsByState(): array;
    /** @deprecated  */
    public function getIssueCountByTypeAndState(): array;
    /** @deprecated  */
    public function getCountsByBoardColumns(): array;
}
