<?php

namespace App\Jira;

use JiraRestApi\ClassSerialize;

class Filter implements \JsonSerializable
{
    use ClassSerialize;

    public int $id;

    public string $name;

    public string $jql;

    public function jsonSerialize(): mixed
    {
        return array_filter(get_object_vars($this), function ($var) {
            return !is_null($var);
        });
    }
}
