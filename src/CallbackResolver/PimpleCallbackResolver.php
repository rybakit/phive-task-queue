<?php

namespace Phive\TaskQueue\CallbackResolver;

use Phive\TaskQueue\ExecutionContext;
use Pimple\Container;
use Symfony\Component\PropertyAccess\PropertyAccess;

class PimpleCallbackResolver implements CallbackResolver
{
    /**
     * @var Container
     */
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function resolve($payload, ExecutionContext $context)
    {
        $accessor = PropertyAccess::createPropertyAccessor();
        $service = $accessor->getValue($payload, 'service');

        return $this->container[$service];
    }
}
