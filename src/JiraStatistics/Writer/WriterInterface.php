<?php

namespace App\JiraStatistics\Writer;

interface WriterInterface
{
    public function writeData(array $statistics);
}