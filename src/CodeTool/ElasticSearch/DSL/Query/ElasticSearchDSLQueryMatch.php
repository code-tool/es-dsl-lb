<?php

declare(strict_types = 1);

namespace CodeTool\ElasticSearch\DSL\Query;

use CodeTool\ElasticSearch\DSL\ElasticSearchDSLQueryInterface;

final class ElasticSearchDSLQueryMatch implements ElasticSearchDSLQueryInterface
{
    private string $name;

    private string $queryString;

    private ?string $operator = null;

    private ?float $cutoff = null;

    private ?string $zeroTermQuery = null;

    public function __construct(string $name, string $queryString)
    {
        $this->name = $name;
        $this->queryString = $queryString;
    }

    public function operator(string $operator): self
    {
        $this->operator = $operator;

        return $this;
    }

    public function cutoff(float $value): self
    {
        $this->cutoff = $value;

        return $this;
    }

    public function zeroTermQuery(string $value): self
    {
        $this->zeroTermQuery = $value;

        return $this;
    }

    public function jsonSerialize(): array
    {
        $query = [];
        $query['query'] = $this->queryString;

        if (null !== $this->operator) {
            $query['operator'] = $this->operator;
        }

        if (null !== $this->cutoff) {
            $query['cutoff_frequency'] = $this->cutoff;
        }

        if (null !== $this->zeroTermQuery) {
            $query['zero_term_query'] = $this->zeroTermQuery;
        }

        return ['match' => [$this->name => $query]];
    }
}
