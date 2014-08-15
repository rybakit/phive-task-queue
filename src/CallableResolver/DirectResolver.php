<?php

namespace Phive\TaskQueue\CallableResolver;

use Phive\TaskQueue\ExecutionContext;
use Phive\TaskQueue\Task\Task;

class DirectResolver implements CallableResolver
{
    public function resolveCallable(Task $task, ExecutionContext $context)
    {
        return $task->getPayload();
    }
}
