<?php

namespace App\Jira\Board\Configuration;

use JiraRestApi\ClassSerialize;

class Filter implements \JsonSerializable
{
    use ClassSerialize;

    public int $id;

    public string $self;

    public function jsonSerialize()
    {
        return array_filter(get_object_vars($this), function ($var) {
            return !is_null($var);
        });
    }
}
