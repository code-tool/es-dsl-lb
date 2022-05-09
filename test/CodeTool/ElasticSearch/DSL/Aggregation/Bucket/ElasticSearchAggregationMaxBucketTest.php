<?php

declare(strict_types=1);

namespace CodeTool\ElasticSearch\DSL\Aggregation\Bucket;

use PHPUnit\Framework\TestCase;

final class ElasticSearchAggregationMaxBucketTest extends TestCase
{
    public function testMaxBucketAggregation(): void
    {
        $agg = (new ElasticSearchAggregationMaxBucket())
            ->bucketPath('the_sum')
            ->gapSkip();

        $this->assertEquals(
            '{"max_bucket":{"buckets_path":"the_sum","gap_policy":"skip"}}',
            \json_encode($agg, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
        );
    }
}

