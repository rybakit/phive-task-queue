<?php

namespace Phive\TaskQueue\Tests;

use Phive\Queue\NoItemAvailableException;
use Phive\Queue\Queue;
use Phive\TaskQueue\ExecutionContext;
use Phive\TaskQueue\Executor;
use Phive\TaskQueue\ExecutorAdapter\ExecutorAdapter;
use Psr\Log\LoggerInterface;

class ExecutorTest extends \PHPUnit_Framework_TestCase
{
    private $adapter;
    private $queue;
    private $logger;

    /**
     * @var Executor
     */
    private $executor;

    protected function setUp()
    {
        $this->adapter = $this->getMockBuilder(ExecutorAdapter::class)->getMock();

        $this->queue = $this->getMockBuilder(Queue::class)->getMock();
        $this->logger = $this->getMockBuilder(LoggerInterface::class)->getMock();

        $context = $this->getMockBuilder(ExecutionContext::class)
            ->disableOriginalConstructor()
            ->getMock();

        $context->expects($this->any())->method('getQueue')->will($this->returnValue($this->queue));
        $context->expects($this->any())->method('getLogger')->will($this->returnValue($this->logger));

        $this->executor = new Executor($this->adapter, $context);
    }

    public function testExecuteReturnsFalseOnEmptyQueue()
    {
        $exception = new NoItemAvailableException($this->queue);
        $this->queue->expects($this->any())->method('pop')->will($this->throwException($exception));

        $this->assertFalse($this->executor->execute());
    }
}
