<?php

declare(strict_types=1);

namespace CodeTool\ElasticSearch\DSL\Query;

use CodeTool\ElasticSearch\DSL\ElasticSearchDSLQueryInterface;

/**
 * RangeQuery matches documents with fields that have terms within a certain range.
 *
 * For details, @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-range-query.html
 */
final class ElasticSearchDSLQueryRange implements ElasticSearchDSLQueryInterface
{
    private string $name;

    private $from;

    private $to;

    private string $timeZone = '';

    private bool $includeLower = true;

    private bool $includeUpper = true;

    private ?float $boost = null;

    private string $queryName = '';

    private string $format = '';

    private string $relation = '';

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function from($from): self
    {
        $this->from = $from;

        return $this;
    }

    public function gt($from): self
    {
        $this->from = $from;
        $this->includeLower = false;

        return $this;
    }

    public function gte($from): self
    {
        $this->from = $from;
        $this->includeLower = true;

        return $this;
    }

    public function to($to): self
    {
        $this->to = $to;

        return $this;
    }

    public function lt($to): self
    {
        $this->to = $to;
        $this->includeUpper = false;

        return $this;
    }

    public function lte($to): self
    {
        $this->to = $to;
        $this->includeUpper = true;

        return $this;
    }

    public function includeLower(bool $includeLower): self
    {
        $this->includeLower = $includeLower;

        return $this;
    }

    public function includeUpper(bool $includeUpper): self
    {
        $this->includeUpper = $includeUpper;

        return $this;
    }

    public function boost(float $boost): self
    {
        $this->boost = $boost;

        return $this;
    }

    public function queryName(string $queryName): self
    {
        $this->queryName = $queryName;

        return $this;
    }

    public function timeZone(string $timeZone): self
    {
        $this->timeZone = $timeZone;

        return $this;
    }

    public function format(string $format): self
    {
        $this->format = $format;

        return $this;
    }

    public function relation(string $relation): self
    {
        $this->relation = $relation;

        return $this;
    }

    public function jsonSerialize(): array
    {
        $params = [
            'include_lower' => $this->includeLower,
            'include_upper' => $this->includeUpper
        ];

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

        if ('' !== $this->relation) {
            $params['relation'] = $this->relation;
        }

        if (null !== $this->boost) {
            $params['boost'] = $this->boost;
        }

        $query = [$this->name => $params];
        if ('' !== $this->queryName) {
            $query['_name'] = $this->queryName;
        }

        return ['range' => $query];
    }
}
