<?php

declare(strict_types=1);

namespace CodeTool\ElasticSearch\DSL\Aggregation;

final class ElasticSearchAggregationIncludeExclude
{
    private string $include = '';

    private string $exclude = '';

    private array $includeValues = [];

    private array $excludeValues = [];

    private int $partition = 0;

    private int $numPartitions = 0;

    public function include(string $regexp): self
    {
        $this->include = $regexp;

        return $this;
    }

    public function includeValues(...$values): self
    {
        $this->includeValues = $values;

        return $this;
    }

    public function exclude(string $regexp): self
    {
        $this->include = $regexp;

        return $this;
    }

    public function excludeValues(...$values): self
    {
        $this->excludeValues = $values;

        return $this;
    }

    public function partition(int $partition): self
    {
        $this->partition = $partition;

        return $this;
    }

    public function numPartitions(int $numPartitions): self
    {
        $this->numPartitions = $numPartitions;

        return $this;
    }

    private function includeToJson()
    {
        if ('' !== $this->include) {
            return $this->include;
        }

        if ([] !== $this->includeValues) {
            return $this->includeValues;
        }

        if ($this->numPartitions > 0) {
            return [
                'partition' => $this->partition,
                'num_partitions' => $this->numPartitions
            ];
        }

        return [];
    }

    private function excludeToJson()
    {
        if ('' !== $this->exclude) {
            return $this->exclude;
        }

        if ([] !== $this->excludeValues) {
            return $this->excludeValues;
        }

        return [];
    }

    public function toArray(): array
    {
        $result = [];
        $includeSection = $this->includeToJson();
        $excludeSection = $this->excludeToJson();

        if ('' !== $includeSection && [] !== $includeSection) {
            $result['include'] = $includeSection;
        }

        if ('' !== $excludeSection && [] !== $excludeSection) {
            $result['exclude'] = $excludeSection;
        }

        return $result;
    }
}
