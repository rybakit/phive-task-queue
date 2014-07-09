<?php

namespace Phive\TaskQueue\ExecutorAdapter;

use Phive\TaskQueue\Task;

class CallableExecutorAdapter implements ExecutorAdapter
{
    public function execute(Task $task)
    {
        list($callable, $args) = $task->getPayload();
        call_user_func_array($callable, $args);
    }
}
