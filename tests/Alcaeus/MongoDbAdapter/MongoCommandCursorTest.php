<?php

namespace Alcaeus\MongoDbAdapter\Tests;
use MongoDB\Driver\ReadPreference;
use MongoDB\Operation\Find;

/**
 * @author alcaeus <alcaeus@alcaeus.org>
 */
class MongoCommandCursorTest extends TestCase
{
    public function testInfo()
    {
        $this->prepareData();
        $cursor = $this->getCollection()->aggregateCursor([['$match' => ['foo' => 'bar']]]);

        $expected = [
            'ns' => 'mongo-php-adapter.test',
            'limit' => 0,
            'batchSize' => 0,
            'skip' => 0,
            'flags' => 0,
            'query' => [
                'aggregate' => 'test',
                'pipeline' => [
                    [
                        '$match' => ['foo' => 'bar']
                    ]
                ],
                'cursor' => new \stdClass(),
            ],
            'fields' => null,
            'started_iterating' => false,
        ];
        $info = $cursor->info();
        $this->assertEquals($expected, $info);

        // Ensure cursor started iterating
        iterator_to_array($cursor);

        $expected['started_iterating'] = true;
        $expected += [
            'id' => 0,
            'at' => 0,
            'numReturned' => 0,
            'server' => 'localhost:27017;-;.;' . getmypid(),
            'host' => 'localhost',
            'port' => 27017,
            'connection_type_desc' => 'STANDALONE',
            'firstBatchAt' => 2,
            'firstBatchNumReturned' => 2,
        ];

        $this->assertEquals($expected, $cursor->info());
    }
}
