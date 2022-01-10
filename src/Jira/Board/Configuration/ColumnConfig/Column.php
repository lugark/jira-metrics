<?php
namespace  App\Jira\Board\Configuration\ColumnConfig;

use JiraRestApi\ClassSerialize;

class Column implements \JsonSerializable
{
    use ClassSerialize;

    /** @var string */
    public $name;

    /** @var MappingStatus[] */
    public $statuses;

    public function jsonSerialize(): mixed
    {
        return array_filter(get_object_vars($this), function ($var) {
            return !is_null($var);
        });
    }

}
