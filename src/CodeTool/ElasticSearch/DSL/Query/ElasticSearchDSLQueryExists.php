<?php

declare(strict_types = 1);

namespace CodeTool\ElasticSearch\DSL\Query;

use CodeTool\ElasticSearch\DSL\ElasticSearchDSLQueryInterface;

/**
 * Check that field exists in the source JSON AND it is not null AND it is not empty array
 *
 * For more details, @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-exists-query.html
 */
final class ElasticSearchDSLQueryExists implements ElasticSearchDSLQueryInterface
{
    private string $fieldName;

    public function __construct(string $fieldName)
    {
        $this->fieldName = $fieldName;
    }

    public function jsonSerialize(): array
    {
        return ['exists' => ['field' => $this->fieldName]];
    }
}
