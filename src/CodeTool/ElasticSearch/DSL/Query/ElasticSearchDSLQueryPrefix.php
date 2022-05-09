<?php

declare(strict_types=1);

namespace CodeTool\ElasticSearch\DSL\Query;

use CodeTool\ElasticSearch\DSL\ElasticSearchDSLQueryInterface;

/**
 * PrefixQuery matches documents that have fields containing terms
 * with a specified prefix (not analyzed).
 *
 * For more details, @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-prefix-query.html
 */
final class ElasticSearchDSLQueryPrefix implements ElasticSearchDSLQueryInterface
{
    private string $name;

    private string $prefix;

    private string $rewrite = '';

    private ?float $boost;

    private string $queryName = '';

    private ?bool $caseInsensitive;

    public function __construct(string $name, string $prefix)
    {
        $this->name = $name;
        $this->prefix = $prefix;
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
        $query = [];

        if (null === $this->boost && '' === $this->queryName && '' === $this->rewrite) {
            $query[$this->name] = $this->prefix;
        } else {
            $suqQuery = ['value' => $this->prefix];

            if ('' !== $this->rewrite) {
                $suqQuery['rewrite'] = $this->rewrite;
            }

            if (null !== $this->boost) {
                $suqQuery['boost'] = $this->boost;
            }

            if (null !== $this->caseInsensitive) {
                $suqQuery['case_insensitive'] = $this->caseInsensitive;
            }

            if ('' !== $this->queryName) {
                $suqQuery['_name'] = $this->queryName;
            }

            $query[$this->name] = $suqQuery;
        }

        return ['prefix' => $query];
    }
}
