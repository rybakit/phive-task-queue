<?php

namespace Phive\TaskQueue\CallbackResolver;

use Phive\TaskQueue\ExecutionContext;

interface CallbackResolver
{
    public function resolve($payload, ExecutionContext $context);
}
