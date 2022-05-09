<?php

declare(strict_types=1);

namespace CodeTool\ElasticSearch\DSL\Query;

use CodeTool\ElasticSearch\DSL\ElasticSearchDSLQueryInterface;


final class ElasticSearchDSLQueryNested implements ElasticSearchDSLQueryInterface
{
    private string $path;

    private ElasticSearchDSLQueryInterface $query;

    private ?float $boost;

    private string $queryName = '';

    private string $scoreMode = '';

    private ?bool $ignoreUnmapped;

    public function __construct(string $path, ElasticSearchDSLQueryInterface $query)
    {
        $this->path = $path;
        $this->query = $query;
    }

    public function queryName(string $queryName): self
    {
        $this->queryName = $queryName;

        return $this;
    }

    public function boost(float $boost): self
    {
        $this->boost = $boost;

        return $this;
    }

    public function ignoreUnmapped(bool $ignoreUnmapped): self
    {
        $this->ignoreUnmapped = $ignoreUnmapped;

        return $this;
    }

    public function scoreMode(string $scoreMode): self
    {
        $this->scoreMode = $scoreMode;

        return $this;
    }

    public function jsonSerialize(): array
    {
        $result = [
            'path' => $this->path,
            'query' => $this->query
        ];

        if ('' !== $this->queryName) {
            $result['_name'] = $this->queryName;
        }

        if ('' !== $this->scoreMode) {
            $result['score_mode'] = $this->scoreMode;
        }

        if (null !== $this->boost) {
            $result['boost'] = $this->boost;
        }

        if (null !== $this->ignoreUnmapped) {
            $result['ignore_unmapped'] = $this->ignoreUnmapped;
        }

        return ['nested' => $result];
    }
}
