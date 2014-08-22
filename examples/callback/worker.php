<?php

require __DIR__.'/../../vendor/autoload.php';
require __DIR__.'/../utils.php';

use Phive\Queue\SysVQueue;
use Phive\TaskQueue\CallbackResolver\DirectCallbackResolver;
use Phive\TaskQueue\ExecutorAdapter\CallbackExecutorAdapter;
use Psr\Log\LoggerInterface;

$callback = function ($payload, LoggerInterface $logger) {
    $logger->info(strrev($payload));
};

$executor = create_executor(
    new CallbackExecutorAdapter(new DirectCallbackResolver($callback)),
    new SysVQueue(0xDEADBEAF, true)
);

while (true) {
    if (!$executor->execute()) {
        sleep(1);
    }
}
