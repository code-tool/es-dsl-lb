<?php

declare(strict_types = 1);

namespace CodeTool\ElasticSearch\DSL\Query;

use CodeTool\ElasticSearch\DSL\ElasticSearchDSLQueryInterface;

/**
 * TermsQuery filters documents that have fields that match any of the provided terms (not analyzed).
 *
 * For more details, @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-terms-query.html
 */
class ElasticSearchDSLQueryTerms implements ElasticSearchDSLQueryInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $values;

    /**
     * @var float|null
     */
    private $boost;

    /**
     * @var string
     */
    private $queryName = '';

    public function __construct(string $name, array $values)
    {
        $this->name = $name;
        $this->values = $values;
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

    public function toArray(): array
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
