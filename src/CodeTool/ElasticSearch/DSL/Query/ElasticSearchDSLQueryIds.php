<?php

declare(strict_types = 1);

namespace CodeTool\ElasticSearch\DSL\Query;

use CodeTool\ElasticSearch\DSL\ElasticSearchDSLQueryInterface;

/**
 * IdsQuery filters documents that only have the provided ids.
 * Note, this query uses the _uid field.
 *
 * For more details, @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-ids-query.html
 */
final class ElasticSearchDSLQueryIds implements ElasticSearchDSLQueryInterface
{
    /**
     * @var string[]
     */
    private array $types;

    /**
     * @var string[]
     */
    private array $values = [];

    private ?float $boost;

    private string $queryName = '';

    /**
     * @param string[] $types
     */
    public function __construct(array $types)
    {
        $this->types = $types;
    }

    public function ids(...$ids): self
    {
        $this->values = array_merge($this->values, $ids);

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

    public function jsonSerialize(): array
    {
        $query = [];

        if (1 === \count($this->types)) {
            $query['type'] = $this->types[0];
        } elseif (1 < \count($this->types)) {
            $query['types'] = $this->types;
        }

        $query['values'] = $this->values;

        if (null !== $this->boost) {
            $query['boost'] = $this->boost;
        }

        if ('' !== $this->queryName) {
            $query['_name'] = $this->queryName;
        }

        return ['ids' => $query];
    }
}
