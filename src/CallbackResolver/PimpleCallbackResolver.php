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
        if ($result = $this->resolveByPath($payload, '[service]', '[args]')) {
            return $result;
        }

        if ($result = $this->resolveByPath($payload, 'service', 'args')) {
            return $result;
        }

        if (is_array($payload) &&
            !empty($payload) &&
            count($payload) <= 2 &&
            $result = $this->resolveByPath($payload, '[0]', '[1]'))
        {
            return $result;
        }

        throw new \InvalidArgumentException('Unable to resolve callback.');
    }

    private function resolveByPath($payload, $callablePath, $argsPath)
    {
        if (!$this->accessor->isReadable($payload, $callablePath)) {
            return;
        }

        $id = $this->accessor->getValue($payload, $callablePath);

        if (!is_string($id)) {
            return;
        }

        $args = $this->accessor->isReadable($payload, $argsPath)
            ? $this->accessor->getValue($payload, $argsPath)
            : [];

        return [$this->container[$this->idPrefix.$id], $args];
    }
}
