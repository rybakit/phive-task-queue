<?php

namespace Phive\TaskQueue;

use Phive\Queue\Queue;
use Psr\Log\LoggerInterface as Logger;

class ExecutionContext
{
    /**
     * @var Queue
     */
    private $queue;

    /**
     * @var Logger
     */
    private $logger;

    public function __construct(Queue $queue, Logger $logger)
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
     * @return Logger
     */
    public function getLogger()
    {
        return $this->logger;
    }
}
