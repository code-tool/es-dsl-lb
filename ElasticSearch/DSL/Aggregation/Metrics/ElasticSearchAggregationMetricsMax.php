<?php

declare(strict_types = 1);

namespace CodeTool\ElasticSearch\DSL\Aggregation\Metrics;

use CodeTool\ElasticSearch\DSL\Aggregation\ElasticSearchAggregationInterface;

class ElasticSearchAggregationMetricsMax implements ElasticSearchAggregationInterface
{
    /**
     * @var string
     */
    private $field;

    /**
     * @var  string
     */
    private $format;

    /**
     * @var ElasticSearchAggregationInterface[]
     */
    private $subAggregations = [];

    /**
     * @var string[]
     */
    private $meta = [];

    /**
     * @param string $field
     *
     * @return ElasticSearchAggregationMetricsMax
     */
    public function field(string $field): ElasticSearchAggregationMetricsMax
    {
        $this->field = $field;

        return $this;
    }

    /**
     * @param string $format
     *
     * @return ElasticSearchAggregationMetricsMax
     */
    public function format(string $format): ElasticSearchAggregationMetricsMax
    {
        $this->format = $format;

        return $this;
    }

    /**
     * @param string                            $name
     * @param ElasticSearchAggregationInterface $aggregation
     *
     * @return ElasticSearchAggregationMetricsMax
     */
    public function subAggregation(
        string $name,
        ElasticSearchAggregationInterface $aggregation
    ): ElasticSearchAggregationMetricsMax {
        $this->subAggregations[$name] = $aggregation;

        return $this;
    }

    /**
     * @param string $metaData
     */
    public function meta(string $metaData)
    {
        $this->meta = $metaData;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $options = [];
        if ('' !== $this->field) {
            $options['field'] = $this->field;
        }

        if ('' !== $this->format && null !== $this->format) {
            $options['format'] = $this->format;
        }

        $result = ['max' => $options];

        if (0 !== count($this->subAggregations)) {
            $result['aggregations'] = array_map(
                function (ElasticSearchAggregationInterface $searchAggregation) {
                    return $searchAggregation->toArray();
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
