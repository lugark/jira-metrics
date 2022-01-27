<?php

namespace App\JiraStatistics\Aggregator;

use App\JiraStatistics\IssueInformation;

class IssueAggregator extends AbstractAggregator
{
    const NAME = 'Issue';
    const BY_TYPE = 'byType';
    const BY_STATE = 'byState';
    const AGGREGATION_COUNT = 'count';
    const AGGREGATION_STORYPOINT = 'storypoint';

    public function getName(): string
    {
        return self::NAME;
    }

    private function aggregateByField(string $getter, $payload): AggregationResult
    {
        $result = new AggregationResult();
        /* @var  IssueInformation $statistic*/
        foreach ($payload as $statistic) {
            $value = $result->getValueByKey(
                $statistic->$getter(),
                [self::AGGREGATION_COUNT => 0, self::AGGREGATION_STORYPOINT => 0.0]
            );
            $value[self::AGGREGATION_COUNT]++;
            $value[self::AGGREGATION_STORYPOINT] += $statistic->getEstimation();
            $result->setValueByKey($statistic->$getter(), $value);
        }
        return $result;
    }

    public function byType($payload): AggregationResult
    {
        return $this->aggregateByField('getIssueType', $payload);
    }

    public function byState($payload): AggregationResult
    {
        return $this->aggregateByField('getStatus', $payload);
    }
}
