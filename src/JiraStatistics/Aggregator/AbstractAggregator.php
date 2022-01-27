<?php

namespace App\JiraStatistics\Aggregator;

abstract class AbstractAggregator implements AggregatorInterface
{
    abstract public function getName(): string;

    public function execute(string $aggregation, $payload): AggregationResult
    {
        if (!method_exists($this, $aggregation)) {
            throw new AggregatorException(
                'Can not find aggregation "' . $aggregation . '" for ' . $this->getName()
            );
        }

        return $this->$aggregation($payload);
    }
}
