<?php

declare(strict_types=1);

namespace CodeTool\ElasticSearch\DSL\Sort;

use CodeTool\ElasticSearch\DSL\Sort\Field\ElasticSearchSortField;

class ElasticSearchSort implements ElasticSearchSortInterface
{
    /**
     * @var ElasticSearchSortField[]
     */
    private $sortFields;

    public function __construct(array $sortFields)
    {
        $this->sortFields = $sortFields;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return array_map(
            function (ElasticSearchSortField $sortField) {
                return $sortField->jsonSerialize();
            },
            $this->sortFields
        );
    }
}
