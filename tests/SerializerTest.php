<?php

namespace Phive\TaskQueue\Tests;

use Phive\TaskQueue\Serializer;

class SerializerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Serializer
     */
    protected $serializer;

    /**
     * @dataProvider getDataToSerialize
     */
    public function testSerializeAndDeserialize($raw)
    {
        $serialized = $this->serializer->serialize($raw);

        $this->assertInternalType('string', $serialized);
        $this->assertEquals($raw, $this->serializer->deserialize($serialized));
    }

    public function getDataToSerialize()
    {
        return [
            [null],
            [false],
            [0],
            [str_repeat('x', 255)],
            [new \stdClass()],
        ];
    }

    protected function setUp()
    {
        $this->serializer = new Serializer();
    }
}
