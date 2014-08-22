<?php

require __DIR__.'/../../vendor/autoload.php';
require __DIR__.'/../utils.php';

use Phive\Queue\SysVQueue;
use Phive\TaskQueue\ExecutorAdapter\ProcessExecutorAdapter;

$executor = create_executor(
    new ProcessExecutorAdapter(),
    new SysVQueue(0xDEADBEAF, true)
);

// main loop
while (true) {
    if (!$executor->execute()) {
        sleep(1);
    }
}
