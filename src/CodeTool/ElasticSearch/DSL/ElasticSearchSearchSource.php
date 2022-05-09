<?php

declare(strict_types = 1);

namespace CodeTool\ElasticSearch\DSL;

use CodeTool\ElasticSearch\DSL\Aggregation\ElasticSearchAggregationInterface;
use CodeTool\ElasticSearch\DSL\Query\ElasticSearchDSLFetchSourceContext;
use CodeTool\ElasticSearch\DSL\Sort\ElasticSearchSortInterface;

class ElasticSearchSearchSource implements ElasticSearchDSLQueryInterface
{
    private ?ElasticSearchDSLQueryInterface $query;

    private ?ElasticSearchDSLQueryInterface $postQuery;

    private ?ElasticSearchSortInterface $sort;

    private int $from = -1;

    private int $size = -1;

    private string $timeout = '';

    private $terminateAfter;

    /**
     * @var string[]
     */
    private array $storedFieldNames = [];

    /**
     * @var ElasticSearchAggregationInterface[]
     */
    private array $aggregations = [];

    private ElasticSearchDSLFetchSourceContext $fetchSourceContext;

    public function query(ElasticSearchDSLQueryInterface $query): ElasticSearchSearchSource
    {
        $this->query = $query;

        return $this;
    }

    public function postFilter(ElasticSearchDSLQueryInterface $postFilter): self
    {
        $this->postQuery = $postFilter;

        return $this;
    }

    public function aggregation(string $name, ElasticSearchAggregationInterface $aggregation): self
    {
        $this->aggregations[$name] = $aggregation;

        return $this;
    }

    public function sort(ElasticSearchSortInterface $sort): self
    {
        $this->sort = $sort;

        return $this;
    }

    public function fetchSource(bool $fetchSource): self
    {
        if (null === $this->fetchSourceContext) {
            $this->fetchSourceContext = new ElasticSearchDSLFetchSourceContext($fetchSource);
        } else {
            $this->fetchSourceContext->setFetchSource($fetchSource);
        }

        return $this;
    }

    public function fetchSourceContext(ElasticSearchDSLFetchSourceContext $fetchSourceContext)
    {
        $this->fetchSourceContext = $fetchSourceContext;

        return $this;
    }

    public function jsonSerialize(): array
    {
        $source = [];

        if (-1 !== $this->from) {
            $source['from'] = $this->from;
        }

        if (-1 !== $this->size) {
            $source['size'] = $this->size;
        }

        if ('' !== $this->timeout) {
            $source['timeout'] = $this->timeout;
        }

        if (null !== $this->terminateAfter) {
            $source['terminate_after'] = $this->terminateAfter;
        }

        if (null !== $this->query) {
            $source['query'] = $this->query->jsonSerialize();
        }

        if (null !== $this->sort) {
            $source['sort'] = $this->sort->jsonSerialize();
        }

        if (null !== $this->fetchSourceContext) {
            $source['_source'] = $this->fetchSourceContext->jsonSerialize();
        }

        if ([] !== $this->storedFieldNames) {
            if (1 === \count($this->storedFieldNames)) {
                $source['stored_fields'] = $this->storedFieldNames[0];
            } else {
                $source['stored_fields'] = $this->storedFieldNames;
            }
        }

        if (null !== $this->postQuery) {
            $source['post_filter'] = $this->postQuery->jsonSerialize();
        }

        if ([] !== $this->aggregations) {
            $source['aggregations'] = array_map(
                function (ElasticSearchAggregationInterface $aggregation) {
                    return $aggregation->jsonSerialize();
                },
                $this->aggregations
            );
        }

        return $source;
    }

    public function setFrom(int $from): self
    {
        $this->from = $from;

        return $this;
    }

    public function setSize(int $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function setStoredFields(string ...$storedFieldNames): self
    {
        $this->storedFieldNames = $storedFieldNames;

        return $this;
    }
}
