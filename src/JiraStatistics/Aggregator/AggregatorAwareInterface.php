<?php

namespace App\JiraStatistics\Aggregator;

interface AggregatorAwareInterface
{
    public function addAggregator(AggregatorInterface $aggregator);
    public function resetAggregator();
}
