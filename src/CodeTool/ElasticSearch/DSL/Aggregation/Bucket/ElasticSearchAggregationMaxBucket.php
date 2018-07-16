<?php

declare(strict_types = 1);

namespace CodeTool\ElasticSearch\DSL\Aggregation\Bucket;

use CodeTool\ElasticSearch\DSL\Aggregation\ElasticSearchAggregationInterface;

/**
 * MaxBucketAggregation is a sibling pipeline aggregation which identifies
 * the bucket(s) with the maximum value of a specified metric in a sibling
 * aggregation and outputs both the value and the key(s) of the bucket(s).
 * The specified metric must be numeric and the sibling aggregation must
 * be a multi-bucket aggregation.
 *
 * For more details, see
 * https://www.elastic.co/guide/en/elasticsearch/reference/6.2/search-aggregations-pipeline-max-bucket-aggregation.html
 */
class ElasticSearchAggregationMaxBucket implements ElasticSearchAggregationInterface
{
    private $format = '';

    private $gapPolicy = '';

    /**
     * @var string[]
     */
    private $meta = [];

    /**
     * @var string[]
     */
    private $bucketPaths = [];

    public function format(string $format)
    {
        $this->format = $format;

        return $this;
    }

    public function gapPolicy(string $gapPolicy)
    {
        $this->gapPolicy = $gapPolicy;

        return $this;
    }

    public function gapInsertZeros()
    {
        return $this->gapPolicy('insert_zeros');
    }

    public function gapSkip()
    {
        return $this->gapPolicy('skip');
    }

    public function bucketPath(string ...$bucketPath)
    {
        $this->bucketPaths += $bucketPath;

        return $this;
    }

    public function meta(array $metaData)
    {
        $this->meta = $metaData;

        return $this;
    }

    public function jsonSerialize()
    {
        $params = [];

        switch (\count($this->bucketPaths)) {
            case 0:
                break;
            case 1:
                $params['buckets_path'] = $this->bucketPaths[0];
                break;
            default:
                $params['buckets_path'] = $this->bucketPaths;
        }

        if ('' !== $this->format) {
            $params['format'] = $this->format;
        }

        if ('' !== $this->gapPolicy) {
            $params['gap_policy'] = $this->gapPolicy;
        }

        $result['max_bucket'] = $params;
        if ([] !== $this->meta) {
            $result['meta'] = $this->meta;
        }

        return $result;
    }
}
