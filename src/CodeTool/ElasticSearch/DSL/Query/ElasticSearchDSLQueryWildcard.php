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
final class ElasticSearchDSLQueryWildcard implements ElasticSearchDSLQueryInterface
{
    private string $name;

    private string $wildcard;

    private ?float $boost = null;

    private string $rewrite = '';

    private string $queryName = '';

    private ?bool $caseInsensitive = null;

    public function __construct(string $name, string $wildcard)
    {
        $this->name = $name;
        $this->wildcard = $wildcard;
    }

    public function rewrite(string $rewrite): self
    {
        $this->rewrite = $rewrite;

        return $this;
    }

    public function boost(float $boost): self
    {
        $this->boost = $boost;

        return $this;
    }

    public function queryName(string $queryName): self
    {
        $this->queryName = $queryName;

        return $this;
    }

    public function caseInsensitive(bool $caseInsensitive): self
    {
        $this->caseInsensitive = $caseInsensitive;

        return $this;
    }

    public function jsonSerialize(): array
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

        if (null !== $this->caseInsensitive) {
            $wq['case_insensitive'] = $this->caseInsensitive;
        }

        return ['wildcard' => [$this->name => $wq]];
    }
}
