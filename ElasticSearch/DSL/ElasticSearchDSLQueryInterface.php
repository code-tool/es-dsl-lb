<?php

declare(strict_types=1);

namespace CodeTool\ElasticSearch\DSL;

interface ElasticSearchDSLQueryInterface
{
    public function toArray(): array;
}
