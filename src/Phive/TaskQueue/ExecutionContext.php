<?php

namespace Phive\TaskQueue;

use Phive\Queue\Queue\QueueInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class ExecutionContext implements ExecutionContextInterface
{
    /**
     * @var QueueInterface
     */
    private $queue;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param QueueInterface       $queue
     * @param LoggerInterface|null $logger
     */
    public function __construct(QueueInterface $queue, LoggerInterface $logger = null)
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
