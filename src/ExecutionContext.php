<?php

namespace Phive\TaskQueue;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class ExecutionContext implements ExecutionContextInterface
{
    /**
     * @var TaskQueue
     */
    private $taskQueue;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param TaskQueue            $taskQueue
     * @param LoggerInterface|null $logger
     */
    public function __construct(TaskQueue $taskQueue, LoggerInterface $logger = null)
    {
        $this->taskQueue = $taskQueue;
        $this->logger = $logger ?: new NullLogger();
    }

    /**
     * {@inheritdoc}
     */
    public function getTaskQueue()
    {
        return $this->taskQueue;
    }

    /**
     * {@inheritdoc}
     */
    public function getLogger()
    {
        return $this->logger;
    }
}
