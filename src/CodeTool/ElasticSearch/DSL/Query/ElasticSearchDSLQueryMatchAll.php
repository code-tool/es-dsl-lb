<?php

declare(strict_types = 1);

namespace CodeTool\ElasticSearch\DSL\Query;

use CodeTool\ElasticSearch\DSL\ElasticSearchDSLQueryInterface;

/**
 * MatchAllQuery is the most simple query, which matches all documents,
 * giving them all a _score of 1.0.
 *
 * For more details, @see https://www.elastic.co/guide/en/elasticsearch/reference/master/query-dsl-match-all-query.html
 */
class ElasticSearchDSLQueryMatchAll implements ElasticSearchDSLQueryInterface
{
    /**
     * @var float
     */
    private $boost;

    public function boost(float $boost)
    {
        $this->boost = $boost;

        return $this;
    }

    public function toArray(): array
    {
        $params = [];

        if (null !== $this->boost) {
            $params['boost'] = $this->boost;
        }

        return ['match_all' => $params];
    }
}
