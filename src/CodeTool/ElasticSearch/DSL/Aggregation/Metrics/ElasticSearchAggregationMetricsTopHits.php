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
final class ElasticSearchAggregationMetricsTopHits implements ElasticSearchAggregationInterface
{
    /**
     * The offset from the first result you want to fetch.
     */
    private ?int $from = null;

    /**
     * Number of top matching hits to return per bucket.
     */
    private ?int $size = null;

    /**
     * How the top matching hits should be sorted.
     */
    private ?ElasticSearchSortInterface $sort = null;

    /**
     * @var ElasticSearchAggregationInterface[]
     */
    private array $subAggregations = [];

    /**
     * @var string[]
     */
    private array $meta = [];

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

    public function from(int $from): self
    {
        $this->from = $from;

        return $this;
    }

    public function size(int $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function sort(ElasticSearchSortInterface $sort): self
    {
        $this->sort = $sort;

        return $this;
    }

    public function meta(array $metaData): self
    {
        $this->meta = $metaData;

        return $this;
    }

    public function jsonSerialize(): array
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

        if ([] !== $this->subAggregations) {
            $result['aggregations'] = array_map(
                static function (ElasticSearchAggregationInterface $searchAggregation) {
                    return $searchAggregation->jsonSerialize();
                },
                $this->subAggregations
            );
        }

        if ([] !== $this->meta) {
            $result['meta'] = $this->meta;
        }

        return $result;
    }
}
