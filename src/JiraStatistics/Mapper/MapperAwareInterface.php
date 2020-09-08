<?php
namespace App\JiraStatistics\Mapper;

interface MapperAwareInterface
{
    public function addStatisticsMapper(MapperInterface $mapper);
}