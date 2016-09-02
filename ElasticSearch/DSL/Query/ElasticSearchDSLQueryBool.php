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
class ElasticSearchDSLQueryBool implements ElasticSearchDSLQueryInterface
{
    /**
     * @var ElasticSearchDSLQueryInterface[]
     */
    private $mustClauses = [];

    /**
     * @var ElasticSearchDSLQueryInterface[]
     */
    private $mustNotClauses = [];

    /**
     * @var ElasticSearchDSLQueryInterface[]
     */
    private $filterClauses = [];

    /**
     * @var ElasticSearchDSLQueryInterface[]
     */
    private $shouldClauses = [];

    /**
     * @var float
     */
    private $boost;

    /**
     * @var bool
     */
    private $disableCoord;

    /**
     * @var string
     */
    private $minimumShouldMatch = '';

    /**
     * @var bool
     */
    private $adjustPureNegative;

    /**
     * @var string
     */
    private $queryName = '';

    public function must(ElasticSearchDSLQueryInterface $query)
    {
        $this->mustClauses[] = $query;

        return $this;
    }

    public function mustNot(ElasticSearchDSLQueryInterface $query)
    {
        $this->mustNotClauses[] = $query;

        return $this;
    }

    public function filter(ElasticSearchDSLQueryInterface $query)
    {
        $this->filterClauses[] = $query;

        return $this;
    }

    public function should(ElasticSearchDSLQueryInterface $query)
    {
        $this->shouldClauses[] = $query;

        return $this;
    }

    public function boost(float $boost)
    {
        $this->boost = $boost;

        return $this;
    }

    public function disableCoord(bool $disableCoord)
    {
        $this->disableCoord = $disableCoord;

        return $this;
    }

    public function minimumShouldMatch(string $minimumShouldMatch)
    {
        $this->minimumShouldMatch = $minimumShouldMatch;

        return $this;
    }

    public function adjustPureNedative(bool $adjustPureNegative)
    {
        $this->adjustPureNegative = $adjustPureNegative;

        return $this;
    }

    public function queryName(string $queryName)
    {
        $this->queryName = $queryName;

        return $this;
    }

    private function causesToValue(array $queries)
    {
        if (0 === $queriesCount = count($queries)) {
            return [];
        }

        if (1 === $queriesCount) {
            return $this->mustClauses[0]->toArray();
        }

        return array_map(
            function (ElasticSearchDSLQueryInterface $query) {
                return $query->toArray();
            },
            $queries
        );
    }

    public function toArray(): array
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
