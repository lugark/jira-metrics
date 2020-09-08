<?php

namespace App\JiraStatistics;

use App\JiraStatistics\Mapper\MapperInterface;
use App\JiraStatistics\Writer\WriterInterface;

class Output
{
    /** @var WriterInterface[] */
    private $writer;

    /** @var MapperInterface[] */
    private $mapper;

    public function __construct(WriterInterface $writer)
    {
        $this->writer[]= $writer;
    }

    public function addWriter(WriterInterface $writer)
    {
        $this->writer[] = $writer;
    }

    public function output(SprintStatistics $statistics)
    {
        foreach ($this->writer as $writer) {
            $writer->writeData($statistics);
        }
    }

}