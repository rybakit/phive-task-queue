<?php

namespace Phive\TaskQueue\ExecutorAdapter;

use Phive\TaskQueue\ExecutionContext;

interface ExecutorAdapter
{
    public function execute($payload, ExecutionContext $context);
}
