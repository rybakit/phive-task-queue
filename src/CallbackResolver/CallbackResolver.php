<?php

namespace Phive\TaskQueue\CallbackResolver;

use Phive\TaskQueue\ExecutionContext;

interface CallbackResolver
{
    /**
     * @param mixed            $payload
     * @param ExecutionContext $context
     *
     * @return callable
     *
     * @throws \InvalidArgumentException
     */
    public function resolve($payload, ExecutionContext $context);
}
