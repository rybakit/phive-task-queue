<?php

namespace Phive\TaskQueue;

interface ExecutionContextInterface
{
    /**
     * @return \Phive\Queue\Queue
     */
    public function getQueue();

    /**
     * @return \Psr\Log\LoggerInterface
     */
    public function getLogger();
}
