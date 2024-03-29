<?php

namespace App\Jira\Board\Configuration;

use JiraRestApi\ClassSerialize;

class SubQuery implements \JsonSerializable
{
    use ClassSerialize;

    public string $query;

    public function jsonSerialize()
    {
        return array_filter(get_object_vars($this), fn($var) => !is_null($var));
    }

}
