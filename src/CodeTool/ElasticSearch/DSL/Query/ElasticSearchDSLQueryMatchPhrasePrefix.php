<?php

declare(strict_types=1);

namespace CodeTool\ElasticSearch\DSL\Query;

use CodeTool\ElasticSearch\DSL\ElasticSearchDSLQueryInterface;

final class ElasticSearchDSLQueryMatchPhrasePrefix implements ElasticSearchDSLQueryInterface
{
    private string $name;

    private string $prefix;

    private string $rewrite = '';

    private ?float $boost = null;

    private string $queryName = '';

    public function __construct(string $name, string $prefix)
    {
        $this->name = $name;
        $this->prefix = $prefix;
    }

    public function rewrite(string $rewrite): self
    {
        $this->rewrite = $rewrite;

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

    public function jsonSerialize(): array
    {
        $query = [];

        if (null === $this->boost && '' === $this->queryName && '' === $this->rewrite) {
            $query[$this->name] = $this->prefix;
        } else {
            $suqQuery = ['prefix' => $this->prefix];

            if ('' !== $this->rewrite) {
                $suqQuery['rewrite'] = $this->rewrite;
            }

            if (null !== $this->boost) {
                $suqQuery['boost'] = $this->boost;
            }

            if ('' !== $this->queryName) {
                $suqQuery['_name'] = $this->queryName;
            }

            $query[$this->name] = $suqQuery;
        }

        return ['match_phrase_prefix' => $query];
    }
}
