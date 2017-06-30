<?php

declare(strict_types=1);

namespace CodeTool\ElasticSearch\DSL\Sort;

use CodeTool\ElasticSearch\DSL\ElasticSearchDSLQueryInterface;

interface ElasticSearchSortInterface extends ElasticSearchDSLQueryInterface
{
    const ASC = 'asc';

    const DESC = 'desc';
}
