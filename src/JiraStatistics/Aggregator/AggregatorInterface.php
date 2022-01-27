<?php

namespace App\JiraStatistics\Aggregator;

use App\JiraStatistics\IssueInformation;

interface AggregatorInterface
{
    public function getName(): string;
    public function execute(string $aggregation, $payload): AggregationResult;
}
