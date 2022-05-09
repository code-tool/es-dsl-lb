<?php

declare(strict_types=1);

namespace CodeTool\ElasticSearch\DSL\Aggregation\Bucket;

use CodeTool\ElasticSearch\DSL\Aggregation\ElasticSearchAggregationInterface;
use CodeTool\ElasticSearch\DSL\ElasticSearchDSLQueryInterface;

final class ElasticSearchAggregationBucketFilter implements ElasticSearchAggregationInterface
{
    private ?ElasticSearchDSLQueryInterface $filter;

    /**
     * @var ElasticSearchAggregationInterface[]
     */
    private array $subAggregations = [];

    /**
     * @var string[]
     */
    private array $meta = [];

    public function filter(ElasticSearchDSLQueryInterface $filter): self
    {
        $this->filter = $filter;

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
        $result = ['filter' => $this->filter->jsonSerialize()];

        if ([] === $this->subAggregations) {
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
