<?php

require __DIR__.'/../vendor/autoload.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Phive\Queue\SysVQueue;
use Phive\TaskQueue\ExecutionContext;
use Phive\TaskQueue\Executor;
use Phive\TaskQueue\ExecutorAdapter\CallableExecutorAdapter;

// create a simple job which will send the greeting to the log
$greeting = function (\stdClass $task, Logger $logger) {
    $logger->info(sprintf('Hello %s!', $task->name));
};

// create a queue
// see a full list of available queues: https://github.com/rybakit/phive-queue#queues
$queue = new SysVQueue(0xDEADBEAF, true);

// create a logger
// can be any PSR-3 compliant logger
$logger = new Logger('worker');
$logger->pushHandler(new StreamHandler(STDOUT, Logger::INFO));

// create an executor
$adapter = new CallableExecutorAdapter($greeting);
$context = new ExecutionContext($queue, $logger);
$executor = new Executor($adapter, $context);

// main loop
while (true) {
    if (!$executor->execute()) {
        sleep(1);
    }
}
