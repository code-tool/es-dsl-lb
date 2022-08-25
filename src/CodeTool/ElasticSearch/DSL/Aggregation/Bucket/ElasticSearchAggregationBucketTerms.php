<?php

declare(strict_types=1);

namespace CodeTool\ElasticSearch\DSL\Aggregation\Bucket;

use CodeTool\ElasticSearch\DSL\Aggregation\ElasticSearchAggregationIncludeExclude;
use CodeTool\ElasticSearch\DSL\Aggregation\ElasticSearchAggregationInterface;
use CodeTool\ElasticSearch\DSL\Aggregation\ElasticSearchAggregationTermsOrder;
use CodeTool\ElasticSearch\ElasticSearchScript;

/**
 * TermsAggregation is a multi-bucket value source based aggregation
 * where buckets are dynamically built - one per unique value.
 * @see: http://www.elasticsearch.org/guide/en/elasticsearch/reference/current/search-aggregations-bucket-terms-aggregation.html
 */
final class ElasticSearchAggregationBucketTerms implements ElasticSearchAggregationInterface
{
    private string $field = '';

    private ?ElasticSearchScript $script = null;

    /**
     * @var ElasticSearchAggregationInterface[]
     */
    private array $subAggregations = [];

    /**
     * @var string[]
     */
    private array $meta = [];

    private ?int $size = null;

    private ?int $shardSize = null;

    private ?int $requiredSize = null;

    private ?int $minDocCount = null;

    private ?int $shardMinDocCount = null;

    private string $valueType = '';

    private ?ElasticSearchAggregationIncludeExclude $includeExclude = null;

    private string $executionHint = '';

    private ?bool $showTermDocCountError = null;

    private string $collectionMode = '';

    /**
     * @var ElasticSearchAggregationTermsOrder[]
     */
    private array $order = []; // ?

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

    public function size(int $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function requiredSize(int $requiredSize): self
    {
        $this->requiredSize = $requiredSize;

        return $this;
    }

    public function shardSize(int $shardSize): self
    {
        $this->shardSize = $shardSize;

        return $this;
    }

    public function minDocCount(int $minDocCount): self
    {
        $this->minDocCount = $minDocCount;

        return $this;
    }

    public function shardMinDocCount(int $shardMinDocCount): self
    {
        $this->shardMinDocCount = $shardMinDocCount;

        return $this;
    }

    public function includeExclude(ElasticSearchAggregationIncludeExclude $includeExclude): self
    {
        $this->includeExclude = $includeExclude;

        return $this;
    }

    // ValueType can be string, long, or double.
    public function valueType(string $valueType): self
    {
        $this->valueType = $valueType;

        return $this;
    }

    public function order(string $order, bool $asc): self
    {
        $this->order[] = new ElasticSearchAggregationTermsOrder($order, $asc);

        return $this;
    }

    public function orderByCount(bool $asc): self
    {
        $this->order[] = new ElasticSearchAggregationTermsOrder('_count', $asc);

        return $this;
    }

    public function orderByCountAsc(): self
    {
        return $this->orderByCount(true);
    }

    public function orderByCountDesc(): self
    {
        return $this->orderByCount(false);
    }

    public function orderByTerm(bool $asc): self
    {
        // "order" : { "_term" : "asc" }
        $this->order[] = new ElasticSearchAggregationTermsOrder('_term', $asc);

        return $this;
    }

    public function orderByTermAsc(): self
    {
        return $this->orderByTerm(true);
    }

    public function orderByTermDesc(): self
    {
        return $this->orderByTerm(false);
    }

    public function orderByKey(bool $asc): self
    {
        // "order" : { "_key" : "asc" }
        $this->order[] = new ElasticSearchAggregationTermsOrder('_key', $asc);

        return $this;
    }

    public function orderByKeyAsc(): self
    {
        return $this->orderByKey(true);
    }

    public function orderByKeyDesc(): self
    {
        return $this->orderByKey(false);
    }

    // OrderByAggregation creates a bucket ordering strategy which sorts buckets
    // based on a single-valued calc get.
    public function orderByAggregation(string $aggName, bool $asc): self
    {
        // {
        //     "aggs" : {
        //         "genders" : {
        //             "terms" : {
        //                 "field" : "gender",
        //                 "order" : { "avg_height" : "desc" }
        //             },
        //             "aggs" : {
        //                 "avg_height" : { "avg" : { "field" : "height" } }
        //             }
        //         }
        //     }
        // }
        $this->order[] = new ElasticSearchAggregationTermsOrder($aggName, $asc);

        return $this;
    }

    // OrderByAggregationAndMetric creates a bucket ordering strategy which
    // sorts buckets based on a multi-valued calc get.
    public function orderByAggregationAndMetric($aggName, string $metric, bool $asc): self
    {
        // {
        //     "aggs" : {
        //         "genders" : {
        //             "terms" : {
        //                 "field" : "gender",
        //                 "order" : { "height_stats.avg" : "desc" }
        //             },
        //             "aggs" : {
        //                 "height_stats" : { "stats" : { "field" : "height" } }
        //             }
        //         }
        //     }
        // }
        $this->order[] = new ElasticSearchAggregationTermsOrder($aggName . '.' . $metric, $asc);

        return $this;
    }

    public function executionHint(string $hint): self
    {
        $this->executionHint = $hint;

        return $this;
    }

    // Collection mode can be depth_first or breadth_first as of 1.4.0.
    public function collectionMode(string $collectionMode): self
    {
        $this->collectionMode = $collectionMode;

        return $this;
    }

    public function showTermDocCountError(bool $showTermDocCountError): self
    {
        $this->showTermDocCountError = $showTermDocCountError;

        return $this;
    }

    public function jsonSerialize(): array
    {
        $opts = [];

        if ('' !== $this->field) {
            $opts['field'] = $this->field;
        }

        if (null !== $this->script) {
            $opts['script'] = $this->script;
        }

        if (null !== $this->size && 0 <= $this->size) {
            $opts['size'] = $this->size;
        }

        if (null !== $this->shardSize && $this->shardSize >= 0) {
            $opts['shard_size'] = $this->shardSize;
        }

        if (null !== $this->requiredSize && $this->requiredSize >= 0) {
            $opts['required_size'] = $this->requiredSize;
        }

        if (null !== $this->minDocCount && $this->minDocCount >= 0) {
            $opts['min_doc_count'] = $this->minDocCount;
        }

        if (null !== $this->shardMinDocCount && $this->shardMinDocCount >= 0) {
            $opts['shard_min_doc_count'] = $this->shardMinDocCount;
        }

        if (null !== $this->showTermDocCountError) {
            $opts['show_term_doc_count_error'] = $this->showTermDocCountError;
        }

        if ('' !== $this->collectionMode) {
            $opts['collect_mode'] = $this->collectionMode;
        }

        if ('' !== $this->valueType) {
            $opts['value_type'] = $this->valueType;
        }

        if ([] !== $this->order) {
            $opts['order'] = $this->order;
        }

        if (null !== $this->includeExclude) {
            $opts += $this->includeExclude->toArray();
        }

        if ('' !== $this->executionHint) {
            $opts['execution_hint'] = $this->executionHint;
        }

        $result['terms'] = $opts;

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
