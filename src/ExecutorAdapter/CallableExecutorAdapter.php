<?php

namespace Phive\TaskQueue\ExecutorAdapter;

use CallableArgumentsResolver\CallableArgumentsResolver;
use Phive\TaskQueue\ExecutionContext;

class CallableExecutorAdapter implements ExecutorAdapter
{
    /**
     * @var CallableArgumentsResolver
     */
    private $resolver;

    public function __construct(callable $callable)
    {
        $this->resolver = new CallableArgumentsResolver($callable);
    }

    public function execute($task, ExecutionContext $context)
    {
        $parameters = [
            'task' => $task,
            $context->getLogger(),
            $context->getQueue(),
            $context,
        ];

        call_user_func_array(
            $this->resolver->getCallable(),
            $this->resolver->resolveArguments($parameters)
        );
    }
}
