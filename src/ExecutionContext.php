<?php

namespace Phive\TaskQueue;

use Phive\Queue\Queue;
use Psr\Log\LoggerInterface;

class ExecutionContext
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
     * @param Queue           $queue
     * @param LoggerInterface $logger
     */
    public function __construct(Queue $queue, LoggerInterface $logger)
    {
        $this->queue = $queue;
        $this->logger = $logger;
    }

    /**
     * @return Queue
     */
    public function getQueue()
    {
        return $this->queue;
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger()
    {
        return $this->logger;
    }
}
