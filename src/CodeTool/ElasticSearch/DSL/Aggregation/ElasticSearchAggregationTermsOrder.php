<?php

namespace CodeTool\ElasticSearch\DSL\Aggregation;

use CodeTool\ElasticSearch\DSL\Sort\ElasticSearchSortInterface;

final class ElasticSearchAggregationTermsOrder implements \JsonSerializable
{
    private string $field;

    private bool $ascending;

    public function __construct(string $field, bool $ascending)
    {
        $this->field = $field;
        $this->ascending = $ascending;
    }

    public function field(string $field): self
    {
        $this->field = $field;

        return $this;
    }

    public function ascending(bool $ascending): self
    {
        $this->ascending = $ascending;

        return $this;
    }

    public function jsonSerialize(): array
    {
        $order = ElasticSearchSortInterface::ASC;
        if (false === $this->ascending) {
            $order = ElasticSearchSortInterface::DESC;
        }

        return [$this->field => $order];
    }
}
