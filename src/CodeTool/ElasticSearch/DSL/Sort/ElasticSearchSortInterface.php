<?php

declare(strict_types=1);

namespace CodeTool\ElasticSearch\DSL\Sort;

use CodeTool\ElasticSearch\DSL\ElasticSearchDSLQueryInterface;

interface ElasticSearchSortInterface extends ElasticSearchDSLQueryInterface
{
    public const ASC = 'asc';

    public const DESC = 'desc';
}
