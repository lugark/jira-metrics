<?php

namespace App\JiraStatistics\Writer;

use App\JiraStatistics\SprintStatistics;

interface WriterInterface
{
    public function writeData(SprintStatistics $statistics);
}