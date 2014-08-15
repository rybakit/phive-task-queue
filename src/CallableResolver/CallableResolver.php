<?php

namespace Phive\TaskQueue\CallableResolver;

use Phive\TaskQueue\ExecutionContext;
use Phive\TaskQueue\Task\Task;

interface CallableResolver
{
    public function resolveCallable(Task $task, ExecutionContext $context);
}
