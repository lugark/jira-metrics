<?php

namespace App\Jira;

use JiraRestApi\ClassSerialize;

class Filter implements \JsonSerializable
{
    use ClassSerialize;

    public int $id;

    public string $name;

    public string $jql;

    public function jsonSerialize()
    {
        return array_filter(get_object_vars($this), fn($var) => !is_null($var));
    }
}
