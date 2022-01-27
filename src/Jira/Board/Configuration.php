<?php
namespace App\Jira\Board;

use App\Jira\Board\Configuration\ColumnConfig;
use App\Jira\Board\Configuration\Filter;
use App\Jira\Board\Configuration\SubQuery;
use JiraRestApi\ClassSerialize;

class Configuration implements \JsonSerializable
{
    use ClassSerialize;

    public int $id;

    public string $self;

    public string $name;

    public string $type;

    public ?Filter $filter;

    public ?SubQuery $subQuery;

    public ?Estimation $estimation;

    public ?ColumnConfig $columnConfig;

    public function jsonSerialize()
    {
        return array_filter(get_object_vars($this), function ($var) {
            return !is_null($var);
        });
    }

    public function hasSubQuery(): bool
    {
        return !(empty($this->subQuery));
    }

    public function hasFilter(): bool
    {
        return !(empty($this->filter));
    }

    public function hasEstimation(): bool
    {
        return !(empty($this->estimation));
    }
}
