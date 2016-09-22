<?php

namespace Phive\TaskQueue\Tests;

use Phive\Queue\NoItemAvailableException;
use Phive\TaskQueue\Executor;

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
        $this->adapter = $this->getMockBuilder('\Phive\TaskQueue\ExecutorAdapter\ExecutorAdapter')->getMock();

        $this->queue = $this->getMockBuilder('\Phive\Queue\Queue')->getMock();
        $this->logger = $this->getMockBuilder('\Psr\Log\LoggerInterface')->getMock();

        $context = $this->getMockBuilder('\Phive\TaskQueue\ExecutionContext')
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
