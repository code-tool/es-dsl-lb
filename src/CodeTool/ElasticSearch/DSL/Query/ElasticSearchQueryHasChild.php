<?php

declare(strict_types = 1);

namespace CodeTool\ElasticSearch\DSL\Query;

use CodeTool\ElasticSearch\DSL\ElasticSearchDSLQueryInterface;

/**
 * HasChildQuery accepts a query and the child type to run against, and results
 * in parent documents that have child docs matching the query.
 *
 * For more details, @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-has-child-query.html
 */
class ElasticSearchQueryHasChild implements ElasticSearchDSLQueryInterface
{
    /**
     * @var ElasticSearchDSLQueryInterface
     */
    private $query;

    /**
     * @var string
     */
    private $childType;

    /**
     * @var string
     */
    private $scoreType = '';

    /**
     * @var int|null
     */
    private $minChildren;

    /**
     * @var int|null
     */
    private $maxChildren;

    /**
     * @var int|null
     */
    private $shortCircuitCutoff;

    //innerHit           *InnerHit

    /**
     * @var float|null
     */
    private $boost;

    /**
     * @var string
     */
    private $queryName = '';

    public function __construct(string $childType, ElasticSearchDSLQueryInterface $query)
    {
        $this->childType = $childType;
        $this->query = $query;
    }

    public function boost(float $boost)
    {
        $this->boost = $boost;

        return $this;
    }

    public function scoreType(string $scoreType)
    {
        $this->scoreType = $scoreType;

        return $this;
    }

    public function minChildren(int $minChildren)
    {
        $this->minChildren = $minChildren;

        return $this;
    }

    public function maxChildren(int $maxChildren)
    {
        $this->maxChildren = $maxChildren;

        return $this;
    }

    public function shortCircuitCutoff(int $shortCircuitCutoff)
    {
        $this->shortCircuitCutoff = $shortCircuitCutoff;

        return $this;
    }

    public function queryName(string $queryName)
    {
        $this->queryName = $queryName;

        return $this;
    }

    public function jsonSerialize()
    {
        $query = [
            'query' => $this->query->jsonSerialize(),
            'type' => $this->childType
        ];

        if (null !== $this->boost) {
            $query['boost'] = $this->boost;
        }

        if ('' !== $this->scoreType) {
            $query['score_type'] = $this->scoreType;
        }

        if (null !== $this->minChildren) {
            $query['min_children'] = $this->minChildren;
        }

        if (null !== $this->maxChildren) {
            $query['max_children'] = $this->maxChildren;
        }

        if (null !== $this->shortCircuitCutoff) {
            $query['short_circuit_cutoff'] = $this->shortCircuitCutoff;
        }

        if ('' !== $this->queryName) {
            $query['_name'] = $this->queryName;
        }

        return ['has_child' => $query];
    }
}
