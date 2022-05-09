<?php

declare(strict_types=1);

namespace CodeTool\ElasticSearch\DSL\Query;

use CodeTool\ElasticSearch\DSL\ElasticSearchDSLQueryInterface;

/**
 * Backported from @url https://github.com/olivere/elastic/blob/release-branch.v5/fetch_source_context.go
 */
final class ElasticSearchDSLFetchSourceContext implements ElasticSearchDSLQueryInterface
{
    /**
     * @var bool
     */
    private bool $fetchSource;

    /**
     * @var string[]
     */
    private array $includes = [];

    /**
     * @var string[]
     */
    private array $excludes = [];

    public function __construct($fetchSource = false)
    {
        $this->fetchSource = $fetchSource;
    }

    public function fetchSource(): bool
    {
        return $this->fetchSource;
    }

    public function setFetchSource(bool $fetchSource): self
    {
        $this->fetchSource = $fetchSource;

        return $this;
    }

    public function includes(string ...$includes): self
    {
        $this->includes = array_merge($this->includes, $includes);

        return $this;
    }

    public function excludes(string ...$excludes): self
    {
        $this->excludes = array_merge($this->excludes, $excludes);

        return $this;
    }

    public function jsonSerialize()
    {
        if (false === $this->fetchSource) {
            return false;
        }

        return [
            'includes' => $this->includes,
            'excludes' => $this->excludes
        ];
    }
}
