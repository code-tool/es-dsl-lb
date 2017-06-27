<?php

declare(strict_types = 1);

namespace CodeTool\ElasticSearch\DSL\Query;

use CodeTool\ElasticSearch\DSL\ElasticSearchDSLQueryInterface;

/**
 * WildcardQuery matches documents that have fields matching a wildcard
 * expression (not analyzed). Supported wildcards are *, which matches
 * any character sequence (including the empty one), and ?, which matches
 * any single character. Note this query can be slow, as it needs to iterate
 * over many terms. In order to prevent extremely slow wildcard queries,
 * a wildcard term should not start with one of the wildcards * or ?.
 * The wildcard query maps to Lucene WildcardQuery.
 *
 * For more details, @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-wildcard-query.html
 */
class ElasticSearchDSLQueryWildcard implements ElasticSearchDSLQueryInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $wildcard;

    /**
     * @var float|null
     */
    private $boost;

    /**
     * @var string
     */
    private $rewrite = '';

    /**
     * @var string
     */
    private $queryName = '';

    public function __construct(string $name, string $wildcard)
    {
        $this->name = $name;
        $this->wildcard = $wildcard;
    }

    public function rewrite(string $rewrite)
    {
        $this->rewrite = $rewrite;

        return $this;
    }

    public function boost(float $boost)
    {
        $this->boost = $boost;

        return $this;
    }

    public function queryName(string $queryName)
    {
        $this->queryName = $queryName;

        return $this;
    }

    public function jsonSerialize()
    {
        $wq = ['wildcard' => $this->wildcard];

        if (null !== $this->boost) {
            $wq['boost'] = $this->boost;
        }

        if ('' !== $this->rewrite) {
            $wq['rewrite'] = $this->rewrite;
        }

        if ('' !== $this->queryName) {
            $wq['_name'] = $this->queryName;
        }

        return ['wildcard' => [$this->name => $wq]];
    }
}
