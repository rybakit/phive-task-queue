<?php

namespace Phive\TaskQueue\Tests;

use Phive\TaskQueue\RetryAwarePayload;

class RetryAwarePayloadTest extends \PHPUnit_Framework_TestCase
{
    const PAYLOAD = 'foobar';

    /**
     * @var retryAwarePayload
     */
    private $retryAwarePayload;

    protected function setUp()
    {
        $this->retryAwarePayload = new RetryAwarePayload(self::PAYLOAD);
    }

    public function testGetPayload()
    {
        $this->assertSame(self::PAYLOAD, $this->retryAwarePayload->getPayload());
    }

    public function testIncRetry()
    {
        $this->assertSame(1, $this->retryAwarePayload->incRetry());
        $this->assertSame(2, $this->retryAwarePayload->incRetry());
    }
}
