<?php

declare(strict_types = 1);

namespace CodeTool\ElasticSearch\DSL;


use CodeTool\ElasticSearch\DSL\Aggregation\ElasticSearchAggregationInterface;

class ElasticSearchSearchSource implements ElasticSearchDSLQueryInterface
{
    /**
     * @var ElasticSearchDSLQueryInterface|null
     */
    private $query;

    /**
     * @var ElasticSearchDSLQueryInterface|null
     */
    private $postQuery;

    private $from = -1;

    private $size = -1;

    private $explain;

    private $version;

    private $sorters = [];

    private $trackScores = false;

    private $minScore;

    /**
     * @var string
     */
    private $timeout = '';

    private $terminateAfter;

    private $fieldNames;

    private $fieldDataFields = [];

    private $scriptFields = [];

    private $fetchSourceContext;

    private $aggregations = [];

    public function query(ElasticSearchDSLQueryInterface $query)
    {
        $this->query = $query;

        return $this;
    }

    public function postFilter(ElasticSearchDSLQueryInterface $postFilter)
    {
        $this->postQuery = $postFilter;

        return $this;
    }

    public function aggregation(string $name, $aggregation)
    {
        $this->aggregations[$name] = $aggregation;

        return $this;
    }

    public function toArray(): array
    {
        $source = [];

        if (-1 !== $this->from) {
            $source['from'] = $this->from;
        }

        if (-1 !== $this->size) {
            $source['size'] = $this->from;
        }

        if (-1 !== $this->timeout) {
            $source['timeout'] = $this->timeout;
        }

        if (null !== $this->terminateAfter) {
            $source['terminate_after'] = $this->terminateAfter;
        }

        if (null !== $this->query) {
            $source['query'] = $this->query->toArray();
        }

        if (null !== $this->postQuery) {
            $source['post_filter'] = $this->postQuery->toArray();
        }

        if ([] !== $this->aggregations) {
            $source['aggregations'] = array_map(
                function (ElasticSearchAggregationInterface $aggregation) {
                    return $aggregation->toArray();
                },
                $this->aggregations
            );
        }

        return $source;
    }
}
