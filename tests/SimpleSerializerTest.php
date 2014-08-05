<?php

namespace Phive\TaskQueue\Tests;

use Phive\TaskQueue\SimpleSerializer;

class SimpleSerializerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SimpleSerializer
     */
    protected $serializer;

    protected function setUp()
    {
        $this->serializer = new SimpleSerializer();
    }

    /**
     * @dataProvider provideDataToSerialize
     */
    public function testSerialization($raw)
    {
        $serialized = $this->serializer->serialize($raw);

        $this->assertInternalType('string', $serialized);
        $this->assertEquals($raw, $this->serializer->deserialize($serialized));
    }

    public function provideDataToSerialize()
    {
        return [
            [null],
            [false],
            [0],
            ['foo bar'],
            [new \stdClass()],
            [[null, false, 0, 'foo bar', new \stdClass(), []]],
        ];
    }
}
