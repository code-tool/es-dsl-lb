<?php

declare(strict_types=1);

namespace CodeTool\ElasticSearch\DSL\Query;

use CodeTool\ElasticSearch\DSL\ElasticSearchDSLQueryInterface;

/**
 * MatchAllQuery is the most simple query, which matches all documents,
 * giving them all a _score of 1.0.
 *
 * For more details, @see https://www.elastic.co/guide/en/elasticsearch/reference/master/query-dsl-match-all-query.html
 */
final class ElasticSearchDSLQueryMatchAll implements ElasticSearchDSLQueryInterface
{
    private ?float $boost;

    public function boost(float $boost): self
    {
        $this->boost = $boost;

        return $this;
    }

    public function jsonSerialize(): array
    {
        $params = [];

        if (null !== $this->boost) {
            $params['boost'] = $this->boost;
        }

        if ([] === $params) {
            $params = new \stdClass();
        }

        return ['match_all' => $params];
    }
}
