<?php

namespace Phive\TaskQueue\Tests\ExecutorAdapter;

use Phive\Queue\Queue;
use Phive\TaskQueue\ExecutionContext;
use Phive\TaskQueue\ExecutorAdapter\ExecutorAdapter;
use Phive\TaskQueue\ExecutorAdapter\RetryableExecutorAdapter;
use Phive\TaskQueue\RetryAwarePayload;
use Phive\TaskQueue\RetryStrategy\RetryStrategy;
use Psr\Log\LoggerInterface;

class RetryableExecutorAdapterTest extends \PHPUnit_Framework_TestCase
{
    private $adapter;
    private $retryStrategy;
    private $retryableAdapter;

    protected function setUp()
    {
        $this->adapter = $this->getMockBuilder(ExecutorAdapter::class)->getMock();
        $this->retryStrategy = $this->getMockBuilder(RetryStrategy::class)->getMock();

        $this->retryableAdapter = new RetryableExecutorAdapter($this->adapter, $this->retryStrategy);
    }

    public function testExecute()
    {
        $this->adapter->expects($this->any())->method('execute')->will($this->throwException(new \Exception()));
        $this->retryStrategy->expects($this->any())->method('getDelay')->with(1)->willReturn(42);

        $queue = $this->getMockBuilder(Queue::class)->getMock();
        $queue->expects($this->once())->method('push')->with($this->isInstanceOf(RetryAwarePayload::class), '+42');

        $logger = $this->getMockBuilder(LoggerInterface::class)->getMock();

        $context = $this->getMockBuilder(ExecutionContext::class)->disableOriginalConstructor()->getMock();
        $context->expects($this->once())->method('getQueue')->willReturn($queue);
        $context->expects($this->any())->method('getLogger')->willReturn($logger);

        $this->retryableAdapter->execute('payload', $context);
    }
}
