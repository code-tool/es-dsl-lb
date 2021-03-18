<?php

declare(strict_types = 1);

namespace CodeTool\ElasticSearch\DSL\Query;

use CodeTool\ElasticSearch\DSL\ElasticSearchDSLQueryInterface;

/**
 * RangeQuery matches documents with fields that have terms within a certain range.
 *
 * For details, @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-range-query.html
 */
class ElasticSearchDSLQueryRange implements ElasticSearchDSLQueryInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $from;

    /**
     * @var string
     */
    private $to;

    /**
     * @var string
     */
    private $timeZone = '';

    /**
     * @var bool
     */
    private $includeLower = true;

    /**
     * @var bool
     */
    private $includeUpper = true;

    /**
     * @var float
     */
    private $boost;

    /**
     * @var string
     */
    private $queryName = '';

    /**
     * @var string
     */
    private $format = '';

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function from(string $from)
    {
        $this->from = $from;

        return $this;
    }

    public function gt($from)
    {
        $this->from = $from;
        $this->includeLower = false;

        return $this;
    }

    public function gte($from)
    {
        $this->from = $from;
        $this->includeLower = true;

        return $this;
    }

    public function to(string $to)
    {
        $this->to = $to;

        return $this;
    }

    public function lt($to)
    {
        $this->to = $to;
        $this->includeUpper = false;

        return $this;
    }

    public function lte($to)
    {
        $this->to = $to;
        $this->includeUpper = true;

        return $this;
    }

    public function includeLower(bool $includeLower)
    {
        $this->includeLower = $includeLower;

        return $this;
    }

    public function includeUpper(bool $includeUpper)
    {
        $this->includeUpper = $includeUpper;

        return $this;
    }

    public function boost(float $boost)
    {
        $this->boost = $boost;

        return $this;
    }

    public function queryName(string $queryName)
    {
        $this->queryName = $queryName;

        return $this;
    }

    public function format(string $format)
    {
        $this->format = $format;

        return $this;
    }

    public function jsonSerialize()
    {

        if (null !== $this->from) {
            $params['from'] = $this->from;
        }

        if (null !== $this->to) {
            $params['to'] = $this->to;
        }


        if ('' !== $this->timeZone) {
            $params['time_zone'] = $this->timeZone;
        }

        if ('' !== $this->format) {
            $params['format'] = $this->format;
        }

        if (null !== $this->boost) {
            $params['boost'] = $this->boost;
        }

        $params['include_lower'] = $this->includeLower;
        $params['include_upper'] = $this->includeUpper;

        $query = [$this->name => $params];
        if ('' !== $this->queryName) {
            $query['_name'] = $this->queryName;
        }

        return ['range' => $query];
    }
}
