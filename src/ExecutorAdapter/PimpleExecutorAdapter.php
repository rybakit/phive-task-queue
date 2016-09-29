<?php

namespace Phive\TaskQueue\ExecutorAdapter;

use ArgumentsResolver\InDepthArgumentsResolver;
use Phive\TaskQueue\ExecutionContext;
use Pimple\Container;

class PimpleExecutorAdapter implements ExecutorAdapter
{
    private $container;
    private $idPrefix;

    public function __construct(Container $container, $idPrefix = null)
    {
        $this->container = $container;
        $this->idPrefix = (string) $idPrefix;
    }

    public function execute($payload, ExecutionContext $context)
    {
        list($name, $arguments) = $payload;

        $callable = $this->container[$this->idPrefix.$name];
        $arguments += (new InDepthArgumentsResolver($callable))->resolve([
            'payload' => $payload,
            $context->getLogger(),
            $context->getQueue(),
            $context,
        ]);

        call_user_func_array($callable, $arguments);
    }
}
