<?php

declare(strict_types = 1);

namespace CodeTool\ElasticSearch\DSL\Aggregation\PipelineBucket;

use CodeTool\ElasticSearch\DSL\Aggregation\ElasticSearchAggregationInterface;
use CodeTool\ElasticSearch\ElasticSearchScript;

class ElasticSearchAggregationBucketSelector implements ElasticSearchAggregationInterface
{
    private $format = '';

    private $gapPolicy = '';

    /**
     * @var ElasticSearchScript
     */
    private $script;

    /**
     * @var ElasticSearchAggregationInterface[]
     */
    private $subAggregations = [];

    /**
     * @var string[]
     */
    private $meta = [];

    /**
     * @var string[]
     */
    private $bucketsPathsMap = [];

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

    public function script(ElasticSearchScript $script)
    {
        $this->script = $script;

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

    public function bucketsPathsMap(array $bucketsPathsMap)
    {
        $this->bucketsPathsMap = $bucketsPathsMap;

        return $this;
    }

    public function addBucketsPath(string $name, string $path)
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

        if (0 !== count($this->subAggregations)) {
            $result['aggregations'] = array_map(
                function (ElasticSearchAggregationInterface $searchAggregation) {
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
