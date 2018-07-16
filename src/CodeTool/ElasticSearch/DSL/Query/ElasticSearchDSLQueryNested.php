<?php
declare(strict_types=1);

namespace CodeTool\ElasticSearch\DSL\Query;

use CodeTool\ElasticSearch\DSL\ElasticSearchDSLQueryInterface;


class ElasticSearchDSLQueryNested implements ElasticSearchDSLQueryInterface
{
    private $queryPath;

    /**
     * @var ElasticSearchDSLQueryBool
     */
    private $boolQuery;

    private $queryName;

    public function __construct(string $path)
    {
        $this->queryPath = $path;
    }

    public function bool(): ElasticSearchDSLQueryBool
    {
        if (null === $this->boolQuery) {
            $this->boolQuery = new ElasticSearchDSLQueryBool();
        }

        return $this->boolQuery;
    }

    public function nestedFieldName(string $fieldName): string
    {
        return sprintf('%s.%s', $this->queryPath, $fieldName);
    }

    public function jsonSerialize()
    {
        $result = [];

        if ('' !== $this->queryName) {
            $result['_name'] = $this->queryName;
        }

        if ('' !== $this->queryPath) {
            $result['path'] = $this->queryPath;
        }

        if (null !== $this->boolQuery) {
            $result['query'] = $this->boolQuery->jsonSerialize();
        }

        return ['nested' => $result];
    }
}
