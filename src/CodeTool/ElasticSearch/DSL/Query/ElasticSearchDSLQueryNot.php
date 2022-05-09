<?php

declare(strict_types = 1);

namespace CodeTool\ElasticSearch\DSL\Query;

use CodeTool\ElasticSearch\DSL\ElasticSearchDSLQueryInterface;

/**
 * NotQuery filters out matched documents using a query.
 *
 * For more details, @see https://www.elastic.co/guide/en/elasticsearch/reference/master/query-dsl-not-query.html
 */
final class ElasticSearchDSLQueryNot implements ElasticSearchDSLQueryInterface
{
    private ElasticSearchDSLQueryInterface $filter;

    private string $queryName = '';

    public function __construct(ElasticSearchDSLQueryInterface $filter)
    {
        $this->filter = $filter;
    }

    public function queryName(string $queryName): self
    {
        $this->queryName = $queryName;

        return $this;
    }

    public function jsonSerialize(): array
    {
        $params = ['query' => $this->filter->jsonSerialize()];

        if ('' !== $this->queryName) {
            $params['_name'] = $this->queryName;
        }

        return ['not' => $params];
    }
}
