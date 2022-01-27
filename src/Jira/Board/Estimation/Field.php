<?php

namespace App\Jira\Board\Estimation;

use JiraRestApi\ClassSerialize;

class Field implements \JsonSerializable
{
    use ClassSerialize;

    /** @var string */
    public $fieldId;

    /** @var string */
    public $displayName;

    public function jsonSerialize()
    {
        return array_filter(get_object_vars($this), function ($var) {
            return !is_null($var);
        });
    }
}
