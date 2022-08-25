<?php

declare(strict_types = 1);

namespace CodeTool\ElasticSearch\DSL\Query;

use CodeTool\ElasticSearch\DSL\ElasticSearchDSLQueryInterface;

/**
 * TermsQuery filters documents that have fields that match any of the provided terms (not analyzed).
 *
 * For more details, @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-terms-query.html
 */
final class ElasticSearchDSLQueryTerms implements ElasticSearchDSLQueryInterface
{
    private string $name;

    private array $values;

    private ?float $boost = null;

    private string $queryName = '';

    public function __construct(string $name, array $values)
    {
        $this->name = $name;
        $this->values = $values;
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

    public function jsonSerialize(): array
    {
        $params = [$this->name => $this->values];

        if (null !== $this->boost) {
            $params['boost'] = $this->boost;
        }

        if ('' !== $this->queryName) {
            $params['_name'] = $this->queryName;
        }

        return ['terms' => $params];
    }
}
