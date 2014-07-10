<?php

namespace Phive\TaskQueue\ExecutorAdapter;

use Phive\TaskQueue\ExecutionContext;
use Phive\TaskQueue\Task;

interface ExecutorAdapter
{
    public function execute(Task $task, ExecutionContext $context);
}
