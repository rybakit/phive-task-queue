<?php

namespace Phive\TaskQueue;

use Phive\Queue\Queue;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class ExecutionContext implements ExecutionContextInterface
{
    /**
     * @var Queue
     */
    private $queue;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param Queue                $queue
     * @param LoggerInterface|null $logger
     */
    public function __construct(Queue $queue, LoggerInterface $logger = null)
    {
        $this->queue = $queue;
        $this->logger = $logger ?: new NullLogger();
    }

    /**
     * {@inheritdoc}
     */
    public function getQueue()
    {
        return $this->queue;
    }

    /**
     * {@inheritdoc}
     */
    public function getLogger()
    {
        return $this->logger;
    }
}
