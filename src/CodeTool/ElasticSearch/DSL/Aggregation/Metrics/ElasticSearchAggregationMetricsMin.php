<?php

declare(strict_types=1);

namespace CodeTool\ElasticSearch\DSL\Aggregation\Metrics;

use CodeTool\ElasticSearch\DSL\Aggregation\ElasticSearchAggregationInterface;
use CodeTool\ElasticSearch\ElasticSearchScript;

final class ElasticSearchAggregationMetricsMin implements ElasticSearchAggregationInterface
{
    private string $field = '';

    private ?ElasticSearchScript $script;

    private string $format = '';

    /**
     * @var ElasticSearchAggregationInterface[]
     */
    private array $subAggregations = [];

    /**
     * @var string[]
     */
    private array $meta = [];

    public function field(string $field): self
    {
        $this->field = $field;

        return $this;
    }

    public function script(ElasticSearchScript $script): self
    {
        $this->script = $script;

        return $this;
    }

    public function format(string $format): self
    {
        $this->format = $format;

        return $this;
    }

    public function subAggregation(string $name, ElasticSearchAggregationInterface $aggregation): self
    {
        $this->subAggregations[$name] = $aggregation;

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
        if ('' !== $this->field) {
            $options['field'] = $this->field;
        }

        if (null !== $this->script) {
            $options['script'] = $this->script;
        }

        if ('' !== $this->format) {
            $options['format'] = $this->format;
        }

        $result = ['min' => $options];

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
