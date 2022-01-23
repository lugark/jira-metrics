<?php
namespace  App\Jira\Board\Configuration\ColumnConfig;

use JiraRestApi\ClassSerialize;

class MappingStatus implements \JsonSerializable
{
    use ClassSerialize;

    /** @var int */
    public $id;

    /** @var string */
    public $self;

    public function jsonSerialize()
    {
        return array_filter(get_object_vars($this), function ($var) {
            return !is_null($var);
        });
    }
}
