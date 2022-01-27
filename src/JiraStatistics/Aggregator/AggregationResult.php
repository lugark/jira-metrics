<?php

namespace App\JiraStatistics\Aggregator;

class AggregationResult implements \Iterator, \Countable
{
    private $position = 0;

    /** @var AggregationItem[] */
    private $results = [];

    public function current(): AggregationItem
    {
        return current($this->results);
    }

    public function next()
    {
        next($this->results);
    }

    public function key()
    {
       return key($this->results);
    }

    public function valid(): bool
    {
       return isset($this->results[key($this->results)]);
    }

    public function rewind()
    {
        reset($this->results);
    }

    public function count(): int
    {
        return count($this->results);
    }

    public function addItem(AggregationItem $item)
    {
        $this->results[$item->getKey()] = $item;
    }

    public function getByKey($key, $default='')
    {
        if ($this->hasKey($key)) {
            return $this->results[$key];
        }

        return $default;
    }

    public function getValueByKey($key, $default='')
    {
        if ($this->hasKey($key)) {
            return $this->results[$key]->getValue();
        }

        return $default;
    }

    public function setValueByKey($key, $value)
    {
        if (!$this->hasKey($key)) {
            $this->addItem(new AggregationItem($key, $value));
        } else {
            $this->results[$key]->setValue($value);
        }
    }

    public function increase($key)
    {
        if (!$this->hasKey($key)) {
            $this->results[$key] = new AggregationItem($key, 0);
        }

        $oldValue = $this->results[$key]->getValue();
        if (!is_int($oldValue)) {
            throw new AggregatorException('Trying to increase non Integer value');
        }

        $this->results[$key]->setValue($oldValue + 1);
    }

    public function decrease($key)
    {
        if (!$this->hasKey($key)) {
            $this->results[$key] = new AggregationItem($key, 0);
        }

        $oldValue = $this->results[$key]->getValue();
        if (!is_int($oldValue)) {
            throw new AggregatorException('Trying to decrease non Integer value');
        }

        $this->results[$key]->setValue($oldValue - 1);
    }

    private function hasKey($key): bool
    {
        return isset($this->results[$key]);
    }

    public function getKeys(): array
    {
        return array_keys($this->results);
    }
}
