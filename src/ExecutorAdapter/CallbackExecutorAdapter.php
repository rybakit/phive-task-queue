<?php

namespace Phive\TaskQueue\ExecutorAdapter;

use ArgumentsResolver as ar;
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

        $arguments = ar\resolve_arguments($callable, [
            'payload' => $payload,
            $context->getLogger(),
            $context->getQueue(),
            $context,
        ]);

        call_user_func_array($callable, $arguments);
    }
}
