<?php

require __DIR__.'/../vendor/autoload.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Phive\Queue\Queue;
use Phive\TaskQueue\ExecutionContext;
use Phive\TaskQueue\Executor;
use Phive\TaskQueue\ExecutorAdapter\ExecutorAdapter;
use Psr\Log\LoggerInterface;

/**
 * @param ExecutorAdapter      $adapter
 * @param Queue                $queue
 * @param LoggerInterface|null $logger
 *
 * @return Executor
 */
function create_executor(ExecutorAdapter $adapter, Queue $queue, LoggerInterface $logger = null)
{
    if (null === $logger) {
        $logger = new Logger('worker');
        $logger->pushHandler(new StreamHandler(STDOUT, Logger::INFO));
    }

    return new Executor($adapter, new ExecutionContext($queue, $logger));
}
