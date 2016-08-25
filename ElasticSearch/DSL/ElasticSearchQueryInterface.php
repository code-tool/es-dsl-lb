<?php

declare(strict_types=1);

namespace CodeTool\ElasticSearch\DSL;

interface ElasticSearchQueryInterface
{
    public function toArray(): array;
}
