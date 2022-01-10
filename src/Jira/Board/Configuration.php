<?php
namespace App\Jira\Board;

use App\Jira\Board\Configuration\Filter;
use App\Jira\Board\Configuration\SubQuery;
use JiraRestApi\ClassSerialize;

class Configuration implements \JsonSerializable
{
    use ClassSerialize;

    /** @var int */
    public $id;

    /** @var string */
    public $self;

    /** @var string */
    public $name;

    /** @var string */
    public $type;

    public ?Filter $filter;

    public ?SubQuery $subQuery;

    /** @var Configuration\ColumnConfig|null */
    public $columnConfig;

    public function jsonSerialize(): mixed
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
}
