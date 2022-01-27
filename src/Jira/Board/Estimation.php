<?php

namespace App\Jira\Board;

use App\Jira\Board\Estimation\Field;
use JiraRestApi\ClassSerialize;

class Estimation implements \JsonSerializable
{
    use ClassSerialize;

    public string $type;

    public Field $field;

    public function jsonSerialize()
    {
        return array_filter(get_object_vars($this), function ($var) {
            return !is_null($var);
        });
    }
}
