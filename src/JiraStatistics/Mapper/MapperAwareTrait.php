<?php
namespace App\JiraStatistics\Mapper;

trait MapperAwareTrait
{
    /**
     * @var MapperInterface[]
     */
    private $mapper;

    public function addStatisticsMapper(MapperInterface $mapper)
    {
        $this->mapper[]= $mapper;
    }
}