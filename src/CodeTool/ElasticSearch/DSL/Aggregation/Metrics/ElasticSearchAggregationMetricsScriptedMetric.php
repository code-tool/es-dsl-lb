<?php

declare(strict_types=1);

namespace CodeTool\ElasticSearch\DSL\Aggregation\Metrics;

use CodeTool\ElasticSearch\DSL\Aggregation\ElasticSearchAggregationInterface;
use CodeTool\ElasticSearch\ElasticSearchScript;

/**
 * ScriptedMetricAggregation is a metric aggregation that executes using scripts to provide a metric output.
 *
 * See: https://www.elastic.co/guide/en/elasticsearch/reference/7.0/search-aggregations-metrics-scripted-metric-aggregation.html
 */
final class ElasticSearchAggregationMetricsScriptedMetric implements ElasticSearchAggregationInterface
{
    private ?ElasticSearchScript $initScript = null;

    private ?ElasticSearchScript $mapScript = null;

    private ?ElasticSearchScript $combineScript = null;

    private ?ElasticSearchScript $reduceScript = null;

    private array $params = [];

    private array $meta = [];

    public function initScript(ElasticSearchScript $script): self
    {
        $this->initScript = $script;

        return $this;
    }

    public function mapScript(ElasticSearchScript $script): self
    {
        $this->mapScript = $script;

        return $this;
    }

    public function combineScript(ElasticSearchScript $script): self
    {
        $this->combineScript = $script;

        return $this;
    }

    public function reduceScript(ElasticSearchScript $script): self
    {
        $this->reduceScript = $script;

        return $this;
    }

    public function param(string $name, $value): self
    {
        if ('_agg' !== $name) {
            $this->params[$name] = $value;
        }

        return $this;
    }

    public function jsonSerialize(): array
    {
        $options = [];

        if ('' !== $this->initScript) {
            $options['init_script'] = $this->initScript;
        }

        if ('' !== $this->mapScript) {
            $options['map_script'] = $this->mapScript;
        }

        if ('' !== $this->combineScript) {
            $options['combine_script'] = $this->combineScript;
        }

        if ('' !== $this->reduceScript) {
            $options['reduce_script'] = $this->reduceScript;
        }

        if ([] !== $this->params) {
            $options['params'] = $this->params;
        }

        $result = ['scripted_metric' => $options];
        if ([] !== $this->meta) {
            $result['meta'] = $this->meta;
        }

        return $result;
    }
}
