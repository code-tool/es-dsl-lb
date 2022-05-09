<?php

declare(strict_types = 1);

namespace CodeTool\ElasticSearch\DSL\Query;

use CodeTool\ElasticSearch\DSL\ElasticSearchDSLQueryInterface;

/**
 * A bool query matches documents matching boolean
 * combinations of other queries.
 *
 * For more details, @see: http://www.elasticsearch.org/guide/reference/query-dsl/bool-query.html
 */
final class ElasticSearchDSLQueryBool implements ElasticSearchDSLQueryInterface
{
    /**
     * @var ElasticSearchDSLQueryInterface[]
     */
    private array $mustClauses = [];

    /**
     * @var ElasticSearchDSLQueryInterface[]
     */
    private array $mustNotClauses = [];

    /**
     * @var ElasticSearchDSLQueryInterface[]
     */
    private array $filterClauses = [];

    /**
     * @var ElasticSearchDSLQueryInterface[]
     */
    private array $shouldClauses = [];

    private ?float $boost;

    private ?bool $disableCoord;

    private string $minimumShouldMatch = '';

    private ?bool $adjustPureNegative;

    private string $queryName = '';

    public function must(ElasticSearchDSLQueryInterface $query): self
    {
        $this->mustClauses[] = $query;

        return $this;
    }

    public function mustNot(ElasticSearchDSLQueryInterface $query): self
    {
        $this->mustNotClauses[] = $query;

        return $this;
    }

    public function filter(ElasticSearchDSLQueryInterface $query): self
    {
        $this->filterClauses[] = $query;

        return $this;
    }

    public function should(ElasticSearchDSLQueryInterface $query): self
    {
        $this->shouldClauses[] = $query;

        return $this;
    }

    public function boost(float $boost): self
    {
        $this->boost = $boost;

        return $this;
    }

    public function disableCoord(bool $disableCoord): self
    {
        $this->disableCoord = $disableCoord;

        return $this;
    }

    public function minimumShouldMatch(string $minimumShouldMatch): self
    {
        $this->minimumShouldMatch = $minimumShouldMatch;

        return $this;
    }

    public function adjustPureNegative(bool $adjustPureNegative): self
    {
        $this->adjustPureNegative = $adjustPureNegative;

        return $this;
    }

    public function queryName(string $queryName): self
    {
        $this->queryName = $queryName;

        return $this;
    }

    private function causesToValue(array $queries): array
    {
        if (0 === $queriesCount = \count($queries)) {
            return [];
        }

        if (1 === $queriesCount) {
            return $queries[0]->jsonSerialize();
        }

        return array_map(
            static function (ElasticSearchDSLQueryInterface $query) {
                return $query->jsonSerialize();
            },
            $queries
        );
    }

    public function jsonSerialize(): array
    {
        $boolClause = [];

        if ([] !== $tmp = $this->causesToValue($this->mustClauses)) {
            $boolClause['must'] = $tmp;
        }

        if ([] !== $tmp = $this->causesToValue($this->mustNotClauses)) {
            $boolClause['must_not'] = $tmp;
        }

        if ([] !== $tmp = $this->causesToValue($this->filterClauses)) {
            $boolClause['filter'] = $tmp;
        }

        if ([] !== $tmp = $this->causesToValue($this->shouldClauses)) {
            $boolClause['should'] = $tmp;
        }

        if (null !== $this->boost) {
            $boolClause['boost'] = $this->boost;
        }

        if (null !== $this->disableCoord) {
            $boolClause['disable_coord'] = $this->disableCoord;
        }

        if ('' !== $this->minimumShouldMatch) {
            $boolClause['minimum_should_match'] = $this->minimumShouldMatch;
        }

        if (null !== $this->adjustPureNegative) {
            $boolClause['adjust_pure_negative'] = $this->adjustPureNegative;
        }

        if ('' !== $this->queryName) {
            $boolClause['_name'] = $this->queryName;
        }

        return ['bool' => $boolClause];
    }
}
