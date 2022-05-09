<?php

declare(strict_types=1);

namespace CodeTool\ElasticSearch\DSL\Sort\Field;

use CodeTool\ElasticSearch\DSL\ElasticSearchDSLQueryInterface;
use CodeTool\ElasticSearch\DSL\Sort\ElasticSearchSortInterface;

class ElasticSearchSortField implements ElasticSearchDSLQueryInterface
{
    private string $field;

    private string $order;

    public function __construct(string $field, bool $asc = true)
    {
        $this->field = $field;
        $this->order = ElasticSearchSortInterface::ASC;
        if (false === $asc) {
            $this->order = ElasticSearchSortInterface::DESC;
        }
    }

    public function jsonSerialize(): array
    {
        return [
            $this->field => [
                'order' => $this->order
            ]
        ];
    }
}
