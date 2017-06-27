<?php

declare(strict_types = 1);

namespace CodeTool\ElasticSearch\DSL\Query;

use CodeTool\ElasticSearch\DSL\ElasticSearchDSLQueryInterface;

class ElasticSearchDSLQueryMatch implements ElasticSearchDSLQueryInterface
{

    /**
     * @var string
     */
    private $queryString;

    /**
     * @var string
     */
    private $operator;

    /**
     * @var float
     */
    private $cutoff;

    /**
     * @var string
     */
    private $zeroTermQuery;
    /**
     * @var string
     */
    private $name;

    public function __construct(string $name, string $queryString)
    {
        $this->name = $name;
        $this->queryString = $queryString;
    }

    public function operator(string $operator): ElasticSearchDSLQueryMatch
    {
        $this->operator = $operator;

        return $this;
    }

    public function cutoff(float $value): ElasticSearchDSLQueryMatch
    {
        $this->cutoff = $value;

        return $this;
    }

    public function zeroTermQuery(string $value): ElasticSearchDSLQueryMatch
    {
        $this->zeroTermQuery = $value;

        return $this;
    }

    public function jsonSerialize()
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
