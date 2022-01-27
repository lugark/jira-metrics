<?php

namespace App\JiraStatistics\Writer;

use App\JiraStatistics\StatisticsInterface;

interface WriterInterface
{
    public function writeData(StatisticsInterface $statistics);
}
