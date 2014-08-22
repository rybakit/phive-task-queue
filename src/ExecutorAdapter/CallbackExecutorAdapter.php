<?php

namespace Phive\TaskQueue\ExecutorAdapter;

use CallableArgumentsResolver as car;
use Phive\TaskQueue\CallbackResolver\CallbackResolver;
use Phive\TaskQueue\ExecutionContext;

class CallbackExecutorAdapter implements ExecutorAdapter
{
    private $resolver;

    public function __construct(CallbackResolver $resolver)
    {
        $this->resolver = $resolver;
    }

    public function execute($payload, ExecutionContext $context)
    {
        $callable = $this->resolver->resolve($payload, $context);

        $arguments = car\resolve_arguments($callable, [
            'payload' => $payload,
            $context->getLogger(),
            $context->getQueue(),
            $context,
        ]);

        call_user_func_array($callable, $arguments);
    }
}
