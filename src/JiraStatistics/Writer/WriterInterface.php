<?php

namespace App\JiraStatistics\Writer;

use App\JiraStatistics\IssueStatisticsInterface;

interface WriterInterface
{
    public function writeData(IssueStatisticsInterface $statistics);
}
