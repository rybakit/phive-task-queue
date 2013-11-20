<?php

namespace Phive\TaskQueue;

interface ExecutionContextInterface
{
    /**
     * @return TaskQueue
     */
    public function getTaskQueue();

    /**
     * @return \Psr\Log\LoggerInterface
     */
    public function getLogger();
}
