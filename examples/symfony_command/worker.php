<?php

require __DIR__.'/../../vendor/autoload.php';
require __DIR__.'/../utils.php';

use Phive\Queue\SysVQueue;
use Phive\TaskQueue\ExecutorAdapter\SymfonyCommandExecutorAdapter;

$executor = create_executor(
    new SymfonyCommandExecutorAdapter(),
    new SysVQueue(0xDEADBEAF, true)
);

while (true) {
    if (!$executor->execute()) {
        sleep(1);
    }
}
