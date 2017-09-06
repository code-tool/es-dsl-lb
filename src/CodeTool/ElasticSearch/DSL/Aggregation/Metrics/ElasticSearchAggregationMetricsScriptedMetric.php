<?php

declare(strict_types=1);

namespace CodeTool\ElasticSearch\DSL\Aggregation\Metrics;

use CodeTool\ElasticSearch\DSL\Aggregation\ElasticSearchAggregationInterface;

/**
 * ScriptedMetricAggregation is a metric aggregation that executes using scripts to provide a metric output.
 *
 * @see: https://www.elastic.co/guide/en/elasticsearch/reference/5.5/search-aggregations-metrics-scripted-metric-aggregation.html
 */
class ElasticSearchAggregationMetricsScriptedMetric implements ElasticSearchAggregationInterface
{
    /**
     * @var string
     */
    private $initScript = '';

    /**
     * @var string
     */
    private $mapScript = '';

    /**
     * @var string
     */
    private $combineScript = '';

    /**
     * @var string
     */
    private $reduceScript = '';

    /**
     * @var ElasticSearchAggregationInterface[]
     */
    private $subAggregations = [];

    public function initScript(string $script): ElasticSearchAggregationMetricsScriptedMetric
    {
        $this->initScript = $script;

        return $this;
    }

    public function mapScript(string $script): ElasticSearchAggregationMetricsScriptedMetric
    {
        $this->mapScript = $script;

        return $this;
    }

    public function combineScript(string $script): ElasticSearchAggregationMetricsScriptedMetric
    {
        $this->combineScript = $script;

        return $this;
    }

    public function reduceScript(string $script): ElasticSearchAggregationMetricsScriptedMetric
    {
        $this->reduceScript = $script;

        return $this;
    }

    public function subAggregation(string $name, ElasticSearchAggregationInterface $subAggregation)
    {
        $this->subAggregations[$name] = $subAggregation;

        return $this;
    }

    public function jsonSerialize()
    {
        $options = [];
        if ('' !== $this->initScript) {
            $options['init_script'] = $this->initScript;
        }

        if ('' === $this->mapScript) {
            throw new \RuntimeException('map_script is required');
        }

        $options['map_script'] = $this->mapScript;

        if ('' !== $this->combineScript) {
            $options['combine_script'] = $this->combineScript;
        }

        if ('' !== $this->reduceScript) {
            $options['reduce_script'] = $this->reduceScript;
        }

        $result = ['scripted_metric' => $options];

        if (0 !== count($this->subAggregations)) {
            $result['aggregations'] = array_map(
                function (ElasticSearchAggregationInterface $searchAggregation) {
                    return $searchAggregation->jsonSerialize();
                },
                $this->subAggregations
            );
        }

        return $result;
    }
}
