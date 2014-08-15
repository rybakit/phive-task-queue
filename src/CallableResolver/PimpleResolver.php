<?php

namespace Phive\TaskQueue\CallableResolver;

use Phive\TaskQueue\ExecutionContext;
use Phive\TaskQueue\Task\Task;
use Pimple\Container;

class PimpleResolver implements CallableResolver
{
    /**
     * @var Container
     */
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function resolve(Task $task, ExecutionContext $context)
    {
        list($serviceName, $args) = $task->getPayload();

        return [$this->container[$serviceName]($context), $args];
    }
}
