<?php

namespace Phive\TaskQueue\CallbackResolver;

use Phive\TaskQueue\ExecutionContext;
use Pimple\Container;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class PimpleCallbackResolver implements CallbackResolver
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @var PropertyAccessorInterface
     */
    private $accessor;

    public function __construct(Container $container, PropertyAccessorInterface $accessor = null)
    {
        $this->container = $container;
        $this->accessor = $accessor ?: PropertyAccess::createPropertyAccessor();
    }

    public function resolve($payload, ExecutionContext $context)
    {
        $service = $this->accessor->getValue($payload, 'service');

        return $this->container[$service];
    }
}
