<?php
namespace App\Jira\Board\Configuration;

use JiraRestApi\ClassSerialize;

class ColumnConfig implements \JsonSerializable
{
    use ClassSerialize;

    /** @var ColumnConfig\Column[]|null */
    public $columns;

    public function jsonSerialize()
    {
        return array_filter(get_object_vars($this), function ($var) {
            return !is_null($var);
        });
    }
}
