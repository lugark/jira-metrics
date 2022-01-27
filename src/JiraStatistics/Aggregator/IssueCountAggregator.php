<?php

namespace App\JiraStatistics\Aggregator;

use App\JiraStatistics\IssueInformation;

class IssueCountAggregator extends AbstractAggregator
{
    const NAME = 'IssueCount';
    const COUNT_BY_TYPE = 'byType';
    const COUNT_BY_STATE = 'byState';

    public function getName(): string
    {
        return self::NAME;
    }
    private function aggregateByField(string $getter, $payload): AggregationResult
    {
        $result = new AggregationResult();
        /* @var  IssueInformation $statistic*/
        foreach ($payload as $statistic) {
            $result->increase($statistic->$getter());
        }
        return $result;
    }

    public function byType($payload): AggregationResult
    {
        return $this->aggregateByField('getIssueType', $payload);
    }

    public function byState($payload): AggregationResult
    {
        $result = new AggregationResult();
        /* @var  IssueInformation $statistic*/
        foreach ($payload as $statistic) {
            $result->increase($statistic->getStatus());
        }
        return $result;
    }
}
