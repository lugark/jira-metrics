<?php

namespace App\JiraStatistics\Aggregator;

trait AggregatorAwareTrait
{
    /** @var AggregatorInterface[] */
    private $aggregator = [];

    public function runAggregator(string $aggregatorName, string $aggregation, $payload): AggregationResult
    {
        if (! isset($this->aggregator[$aggregatorName])) {
            throw new AggregatorException("Could not find aggregator: " . $aggregatorName);
        }

        try {
            return $this->aggregator[$aggregatorName]->execute($aggregation, $payload);
        } catch (\Exception $exception) {
            throw new AggregatorException($exception->getMessage(), $exception->getCode());
        }
    }

    public function addAggregator(AggregatorInterface $aggregator)
    {
        $this->aggregator[$aggregator->getName()] = $aggregator;
    }

    public function resetAggregator()
    {
        $this->aggregator = [];
    }

}
