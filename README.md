Phive Task Queue
================
[![Build Status](https://secure.travis-ci.org/rybakit/phive-task-queue.png?branch=master)](http://travis-ci.org/rybakit/phive-task-queue)

A lightweight task queue on top of the [Phive Queue](https://github.com/rybakit/phive-queue).


## Installation

The recommended way to install Phive Task Queue is through [Composer](http://getcomposer.org):

```sh
$ composer require rybakit/phive-task-queue:~1.0@dev
```


## Usage example

```php
// worker.php

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
```

```php
// client.php
use Phive\Queue\SysVQueue;

$queue = new SysVQueue(0xDEADBEAF, true);

// send a task/payload object to the queue and delay execution for 5 seconds
// see supported item types: https://github.com/rybakit/phive-queue#item-types
$queue->push((object) ['name' => 'Stranger'], '+5 seconds');
```


## License

Phive Task Queue is released under the MIT License. See the bundled [LICENSE](LICENSE) file for details.
