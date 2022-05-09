<?php

declare(strict_types=1);

namespace CodeTool\ElasticSearch\DSL\Sort;

use CodeTool\ElasticSearch\DSL\Sort\Field\ElasticSearchSortField;

class ElasticSearchSort implements ElasticSearchSortInterface
{
    /**
     * @var ElasticSearchSortField[]
     */
    private array $sortFields;

    public function __construct(array $sortFields)
    {
        $this->sortFields = $sortFields;
    }

    public function jsonSerialize(): array
    {
        return array_map(
            static function (ElasticSearchSortField $sortField) {
                return $sortField->jsonSerialize();
            },
            $this->sortFields
        );
    }
}
