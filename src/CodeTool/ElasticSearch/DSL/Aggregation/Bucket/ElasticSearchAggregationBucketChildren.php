<?php

declare(strict_types=1);

namespace CodeTool\ElasticSearch\DSL\Aggregation\Bucket;

use CodeTool\ElasticSearch\DSL\Aggregation\ElasticSearchAggregationInterface;

/**
 * ChildrenAggregation is a special single bucket aggregation that enables
 * aggregating from buckets on parent document types to buckets on child documents.
 * It is available from 1.4.0.Beta1 upwards.
 * @see: http://www.elasticsearch.org/guide/en/elasticsearch/reference/current/search-aggregations-bucket-children-aggregation.html
 */
final class ElasticSearchAggregationBucketChildren implements ElasticSearchAggregationInterface
{
    private string $type = '';

    /**
     * @var ElasticSearchAggregationInterface[]
     */
    private array $subAggregations = [];

    /**
     * @var string[]
     */
    private array $meta = [];

    public function type(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function subAggregation(string $name, ElasticSearchAggregationInterface $subAggregation): self
    {
        $this->subAggregations[$name] = $subAggregation;

        return $this;
    }

    public function meta(array $metaData): self
    {
        $this->meta = $metaData;

        return $this;
    }

    public function jsonSerialize(): array
    {
        $result = ['children' => ['type' => $this->type]];

        if ([] !== $this->subAggregations) {
            $result['aggregations'] = array_map(
                static function (ElasticSearchAggregationInterface $searchAggregation) {
                    return $searchAggregation->jsonSerialize();
                },
                $this->subAggregations
            );
        }

        if ([] === $this->meta) {
            $result['meta'] = $this->meta;
        }

        return $result;
    }
}
