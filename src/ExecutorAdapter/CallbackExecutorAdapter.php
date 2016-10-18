<?php

namespace Phive\TaskQueue\ExecutorAdapter;

use ArgumentsResolver\InDepthArgumentsResolver;
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
        list($callable, $arguments) = $this->resolver->resolve($payload, $context);

        $arguments = array_merge($arguments, [
            'payload' => $payload,
            $context->getLogger(),
            $context->getQueue(),
            $context,
        ]);

        $arguments = (new InDepthArgumentsResolver($callable))->resolve($arguments);

        call_user_func_array($callable, $arguments);
    }
}
