<?php

declare(strict_types=1);

namespace CodeTool\ElasticSearch\DSL\Sort\Field;

use CodeTool\ElasticSearch\DSL\ElasticSearchDSLQueryInterface;
use CodeTool\ElasticSearch\DSL\Sort\ElasticSearchSortInterface;

class ElasticSearchSortField implements ElasticSearchDSLQueryInterface
{
    /**
     * @var string
     */
    private $field;

    /**
     * @var string
     */
    private $order;

    public function __construct(string $field, bool $asc = true)
    {
        $this->field = $field;
        $this->order = ElasticSearchSortInterface::ASC;
        if (false === $asc) {
            $this->order = ElasticSearchSortInterface::DESC;
        }
    }

    public function jsonSerialize()
    {
        return [
            $this->field => [
                'order' => $this->order
            ]
        ];
    }
}
