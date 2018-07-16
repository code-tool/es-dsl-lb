<?php

declare(strict_types = 1);

namespace CodeTool\ElasticSearch\DSL\Aggregation\Bucket;

use CodeTool\ElasticSearch\DSL\Aggregation\ElasticSearchAggregationInterface;

class ElasticSearchAggregationMaxBucket implements ElasticSearchAggregationInterface
{
    /**
     * @var string
     */
    private $bucketPath;

    /**
     * @var ElasticSearchAggregationInterface[]
     */
    private $subAggregations = [];

    /**
     * @var string[]
     */
    private $meta = [];

    public function bucketPath(string $bucketPath)
    {
        $this->bucketPath = $bucketPath;

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

    public function jsonSerialize()
    {
        $result = ['max_bucket' => ['buckets_path' => $this->bucketPath]];

        if (0 !== \count($this->subAggregations)) {
            $result['aggregations'] = $this->subAggregations;
        }

        if (0 !== \count($this->meta)) {
            $result['meta'] = $this->meta;
        }

        return $result;
    }
}
