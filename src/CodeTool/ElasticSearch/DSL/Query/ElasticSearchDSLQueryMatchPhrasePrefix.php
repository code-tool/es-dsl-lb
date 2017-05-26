<?php

declare(strict_types = 1);

namespace CodeTool\ElasticSearch\DSL\Query;

use CodeTool\ElasticSearch\DSL\ElasticSearchDSLQueryInterface;

class ElasticSearchDSLQueryMatchPhrasePrefix implements ElasticSearchDSLQueryInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $prefix;

    /**
     * @var string
     */
    private $rewrite = '';

    /**
     * @var float|null
     */
    private $boost;

    /**
     * @var string
     */
    private $queryName = '';

    public function __construct(string $name, string $prefix)
    {
        $this->name = $name;
        $this->prefix = $prefix;
    }

    public function rewrite(string $rewrite)
    {
        $this->rewrite = $rewrite;

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

    public function toArray(): array
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
