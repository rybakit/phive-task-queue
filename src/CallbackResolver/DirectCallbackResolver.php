<?php

namespace Phive\TaskQueue\CallbackResolver;

use Phive\TaskQueue\ExecutionContext;

class DirectCallbackResolver implements CallbackResolver
{
    private $callback;

    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    public function resolve($payload, ExecutionContext $context)
    {
        return [$this->callback, [$payload]];
    }
}
