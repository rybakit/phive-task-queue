<?php

require __DIR__.'/../../vendor/autoload.php';
require __DIR__.'/../utils.php';

use Phive\Queue\SysVQueue;
use Phive\TaskQueue\ExecutorAdapter\SymfonyCommandExecutorAdapter;
use Symfony\Component\Console\Application;

$app = new Application();

$executor = create_executor(
    new SymfonyCommandExecutorAdapter($app),
    new SysVQueue(0xDEADBEAF, true)
);

while (true) {
    if (!$executor->execute()) {
        sleep(1);
    }
}
