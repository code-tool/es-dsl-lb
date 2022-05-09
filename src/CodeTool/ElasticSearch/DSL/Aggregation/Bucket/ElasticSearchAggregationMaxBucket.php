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
final class ElasticSearchAggregationMaxBucket implements ElasticSearchAggregationInterface
{
    private string $format = '';

    private string $gapPolicy = '';

    /**
     * @var string[]
     */
    private array $meta = [];

    /**
     * @var string[]
     */
    private array $bucketPaths = [];

    public function format(string $format): self
    {
        $this->format = $format;

        return $this;
    }

    public function gapPolicy(string $gapPolicy): self
    {
        $this->gapPolicy = $gapPolicy;

        return $this;
    }

    public function gapInsertZeros(): self
    {
        return $this->gapPolicy('insert_zeros');
    }

    public function gapSkip(): self
    {
        return $this->gapPolicy('skip');
    }

    public function bucketPath(string ...$bucketPath): self
    {
        $this->bucketPaths += $bucketPath;

        return $this;
    }

    public function meta(array $metaData): self
    {
        $this->meta = $metaData;

        return $this;
    }

    public function jsonSerialize(): array
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
