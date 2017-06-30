<?php

declare(strict_types=1);

namespace CodeTool\ElasticSearch\DSL\Aggregation\Metrics;

use CodeTool\ElasticSearch\DSL\Aggregation\ElasticSearchAggregationInterface;
use CodeTool\ElasticSearch\DSL\Sort\ElasticSearchSortInterface;

/**
 * TopHitsAggregation is metric aggregator keeps track of the
 * most relevant document being aggregated. This aggregator
 * is intended to be used as a sub aggregator, so that the top
 * matching documents can be aggregated per bucket.
 * @see: http://www.elasticsearch.org/guide/en/elasticsearch/reference/current/search-aggregations-metrics-top-hits-aggregation.html
 */
class ElasticSearchAggregationMetricsTopHits implements ElasticSearchAggregationInterface
{
    /**
     * The offset from the first result you want to fetch.
     *
     * @var int
     */
    private $from;

    /**
     * Number of top matching hits to return per bucket.
     *
     * @var int
     */
    private $size;

    /**
     * How the top matching hits should be sorted.
     *
     * @var ElasticSearchSortInterface|null
     */
    private $sort;

    /**
     * @var ElasticSearchAggregationInterface[]
     */
    private $subAggregations = [];

    /**
     * @var string[]
     */
    private $meta = [];

    /**
     * @param string                            $name
     * @param ElasticSearchAggregationInterface $aggregation
     *
     * @return ElasticSearchAggregationMetricsTopHits
     */
    public function subAggregation(
        string $name,
        ElasticSearchAggregationInterface $aggregation
    ): ElasticSearchAggregationMetricsTopHits {
        $this->subAggregations[$name] = $aggregation;

        return $this;
    }

    /**
     * @param int $from
     *
     * @return ElasticSearchAggregationMetricsTopHits
     */
    public function from(int $from): ElasticSearchAggregationMetricsTopHits
    {
        $this->from = $from;

        return $this;
    }

    /**
     * @param int $size
     *
     * @return ElasticSearchAggregationMetricsTopHits
     */
    public function size(int $size): ElasticSearchAggregationMetricsTopHits
    {
        $this->size = $size;

        return $this;
    }

    /**
     * @param ElasticSearchSortInterface $sort
     *
     * @return ElasticSearchAggregationMetricsTopHits
     */
    public function sort(ElasticSearchSortInterface $sort): ElasticSearchAggregationMetricsTopHits
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * @param array $metaData
     *
     * @return ElasticSearchAggregationMetricsTopHits
     */
    public function meta(array $metaData): ElasticSearchAggregationMetricsTopHits
    {
        $this->meta = $metaData;

        return $this;
    }


    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $options = [];

        if (null !== $this->from) {
            $options['from'] = $this->from;
        }

        if (null !== $this->size) {
            $options['size'] = $this->size;
        }

        if (null !== $this->sort) {
            $options['sort'] = $this->sort->jsonSerialize();
        }

        $result = ['top_hits' => $options];

        if (0 !== count($this->subAggregations)) {
            $result['aggregations'] = array_map(
                function (ElasticSearchAggregationInterface $searchAggregation) {
                    return $searchAggregation->jsonSerialize();
                },
                $this->subAggregations
            );
        }

        if (0 !== count($this->meta)) {
            $result['meta'] = $this->meta;
        }

        return $result;
    }
}
