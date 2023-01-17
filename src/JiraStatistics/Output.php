<?php

namespace App\JiraStatistics;

use App\JiraStatistics\Writer\WriterInterface;

class Output
{
    /** @var WriterInterface[] */
    private ?array $writer = null;

    public function __construct(WriterInterface $writer=null)
    {
        if (!empty($writer)) {
            $this->writer[] = $writer;
        }
    }

    public function addWriter(WriterInterface $writer)
    {
        $this->writer[] = $writer;
    }

    public function output(IssueStatisticsInterface $statistics)
    {
        foreach ($this->writer as $writer) {
            $writer->writeData($statistics);
        }
    }
}
