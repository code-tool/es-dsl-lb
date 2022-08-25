<?php

declare(strict_types=1);

namespace CodeTool\ElasticSearch\DSL\Query;

use CodeTool\ElasticSearch\DSL\ElasticSearchDSLQueryInterface;

/**
 * TermQuery finds documents that contain the exact term specified
 * in the inverted index.
 *
 * For details, @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-term-query.html
 */
final class ElasticSearchDSLQueryTerm implements ElasticSearchDSLQueryInterface
{
    private string $name;

    private $value;

    private ?float $boost = null;

    private string $queryName = '';

    private ?bool $caseInsensitive = null;

    public function __construct(string $name, $value)
    {
        $this->name = $name;
        $this->value = $value;
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

        if (null === $this->boost && '' === $this->queryName) {
            $query[$this->name] = $this->value;
        } else {
            $suqQuery = ['value' => $this->value];

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

        return ['term' => $query];
    }
}
