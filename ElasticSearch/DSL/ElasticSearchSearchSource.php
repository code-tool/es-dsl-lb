<?php

declare(strict_types = 1);

namespace CodeTool\ElasticSearch\DSL;


class ElasticSearchSearchSource implements ElasticSearchDSLQueryInterface
{
    /**
     * @var ElasticSearchDSLQueryInterface|null
     */
    private $query;

    /**
     * @var ElasticSearchDSLQueryInterface|null
     */
    private $postQuery;

    private $from = -1;

    private $size = -1;

    private $explain;

    private $version;

    private $sorters = [];

    private $trackScores = false;

    private $minScore;

    private $timeout;

    private $terminateAfter;

    private $fieldNames;

    private $fieldDataFields = [];

    private $scriptFields = [];

    private $fetchSourceContext;

    private $aggregations = [];

    public function query(ElasticSearchDSLQueryInterface $query)
    {
        $this->query = $query;

        return $this;
    }

    public function postFilter(ElasticSearchDSLQueryInterface $postFilter)
    {
        $this->postQuery = $postFilter;

        return $this;
    }

    public function aggregation(string $name, $aggregation)
    {
        $this->aggregations[$name] = $aggregation;

        return $this;
    }

    public function toArray(): array
    {
        return [];
    }
}
