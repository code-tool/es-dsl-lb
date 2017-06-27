<?php

declare(strict_types = 1);

namespace CodeTool\ElasticSearch\DSL\Aggregation\Bucket;

use CodeTool\ElasticSearch\DSL\Aggregation\ElasticSearchAggregationInterface;

/**
 * ChildrenAggregation is a special single bucket aggregation that enables
 * aggregating from buckets on parent document types to buckets on child documents.
 * It is available from 1.4.0.Beta1 upwards.
 * @see: http://www.elasticsearch.org/guide/en/elasticsearch/reference/current/search-aggregations-bucket-children-aggregation.html
 */
class ElasticSearchAggregationBucketChildren implements ElasticSearchAggregationInterface
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var ElasticSearchAggregationInterface[]
     */
    private $subAggregations = [];

    /**
     * @var string[]
     */
    private $meta = [];

    public function type(string $type)
    {
        $this->type = $type;

        return $this;
    }

    public function subAggregation(string $name, ElasticSearchAggregationInterface $subAggregation)
    {
        $this->subAggregations[$name] = $subAggregation;

        return $this;
    }

    public function meta(array $metaData)
    {
        $this->meta = $metaData;

        return $this;
    }

    public function jsonSerialize(): array
    {
        $result = ['children' => ['type' => $this->type]];

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
