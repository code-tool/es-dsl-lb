<?php

declare(strict_types=1);

namespace CodeTool\ElasticSearch\DSL\Aggregation\PipelineBucket;

use CodeTool\ElasticSearch\DSL\Aggregation\ElasticSearchAggregationInterface;
use CodeTool\ElasticSearch\ElasticSearchScript;

final class ElasticSearchAggregationBucketSelector implements ElasticSearchAggregationInterface
{
    private string $format = '';

    private string $gapPolicy = '';

    private ?ElasticSearchScript $script;

    /**
     * @var ElasticSearchAggregationInterface[]
     */
    private array $subAggregations = [];

    /**
     * @var string[]
     */
    private array $meta = [];

    /**
     * @var string[]
     */
    private array $bucketsPathsMap = [];

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

    public function script(ElasticSearchScript $script): self
    {
        $this->script = $script;

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

    public function bucketsPathsMap(array $bucketsPathsMap): self
    {
        $this->bucketsPathsMap = $bucketsPathsMap;

        return $this;
    }

    public function addBucketsPath(string $name, string $path): self
    {
        $this->bucketsPathsMap[$name] = $path;

        return $this;
    }

    public function jsonSerialize(): array
    {
        $params = [];

        if ('' !== $this->format) {
            $params['format'] = $this->format;
        }

        if ('' !== $this->gapPolicy) {
            $params['gap_policy'] = $this->gapPolicy;
        }

        if ([] !== $this->bucketsPathsMap) {
            $params['buckets_path'] = $this->bucketsPathsMap;
        }

        if ([] !== $this->bucketsPathsMap) {
            $params['buckets_path'] = $this->bucketsPathsMap;
        }

        if (null !== $this->script) {
            $params['script'] = $this->script->jsonSerialize();
        }

        $result['bucket_selector'] = $params;

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
