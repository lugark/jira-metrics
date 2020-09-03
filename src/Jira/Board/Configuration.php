<?php
namespace App\Jira\Board;

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

    /** @var Configuration\ColumnConfig|null */
    public $columnConfig;

    public function jsonSerialize()
    {
        return array_filter(get_object_vars($this), function ($var) {
            return !is_null($var);
        });
    }

}