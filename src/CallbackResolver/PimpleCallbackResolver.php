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
     * @var string
     */
    private $idPrefix;

    /**
     * @var PropertyAccessorInterface
     */
    private $accessor;

    public function __construct(Container $container, $idPrefix = null)
    {
        $this->container = $container;
        $this->idPrefix = (string) $idPrefix;
        $this->accessor = PropertyAccess::createPropertyAccessorBuilder()
            ->enableExceptionOnInvalidIndex()
            ->getPropertyAccessor();
    }

    public function resolve($payload, ExecutionContext $context)
    {
        $id = null;

        if ($this->accessor->isReadable($payload, '[service]')) {
            $id = $this->accessor->getValue($payload, '[service]');
        } else if ($this->accessor->isReadable($payload, 'service')) {
            $id = $this->accessor->getValue($payload, 'service');
        } else if (is_array($payload) && !empty($payload[0]) && count($payload) <= 2) {
            $id = $payload[0];
        }

        if (is_string($id)) {
            return $this->container[$this->idPrefix.$id];
        }

        throw new \InvalidArgumentException('Unable to resolve the callback.');
    }
}
