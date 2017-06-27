<?php

declare(strict_types = 1);

namespace CodeTool\ElasticSearch\DSL\Query;

use CodeTool\ElasticSearch\DSL\ElasticSearchDSLQueryInterface;

/**
 * TermQuery finds documents that contain the exact term specified
 * in the inverted index.
 *
 * For details, @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-term-query.html
 */
class ElasticSearchDSLQueryTerm implements ElasticSearchDSLQueryInterface
{
    /**
     * @var string
     */
    private $name = '';

    private $value;

    /**
     * @var float|null
     */
    private $boost;

    /**
     * @var string
     */
    private $queryName = '';

    public function __construct(string $name, $value)
    {
        $this->name = $name;
        $this->value = $value;
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
        $query = [];

        if (null === $this->boost && '' === $this->queryName) {
            $query[$this->name] = $this->value;
        } else {
            $suqQuery = ['value' => $this->value];

            if (null !== $this->boost) {
                $suqQuery['boost'] = $this->boost;
            }

            if ('' !== $this->queryName) {
                $suqQuery['_name'] = $this->queryName;
            }

            $query[$this->name] = $suqQuery;
        }


        return ['term' => $query];
    }
}
