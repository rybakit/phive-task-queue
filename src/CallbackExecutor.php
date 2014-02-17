<?php

namespace Phive\TaskQueue;

class CallbackExecutor extends AbstractExecutor
{
    /**
     * @var callable
     */
    private $callback;

    /**
     * @var \ReflectionMethod|\ReflectionObject|\ReflectionFunction
     */
    private $reflection;

    public function __construct(ExecutionContextInterface $context, callable $callback)
    {
        parent::__construct($context);

        $this->callback = $callback;
    }

    protected function doExecute(TaskInterface $task)
    {
        $parameters = $this->getReflection()->getParameters();

        $arguments = [$task->getPayload()];
        array_shift($parameters);

        foreach ($parameters as $param) {
            if ($param->getClass() && $param->getClass()->isInstance($this->context)) {
                $arguments[] = $this->context;
            } elseif ($param->isDefaultValueAvailable()) {
                $arguments[] = $param->getDefaultValue();
            } else {
                if (is_array($this->callback)) {
                    $repr = sprintf('%s::%s()', get_class($this->callback[0]), $this->callback[1]);
                } elseif (is_object($this->callback)) {
                    $repr = get_class($this->callback);
                } else {
                    $repr = $this->callback;
                }

                throw new \InvalidArgumentException(sprintf(
                    'Callback "%s" requires that you provide a value for the "$%s" argument '.
                    '(because there is no default value or because there is a non optional argument after this one).',
                    $repr,
                    $param->name
                ));
            }
        }

        return call_user_func_array($this->callback, $arguments);
    }

    private function getReflection()
    {
        if (null !== $this->reflection) {
            return $this->reflection;
        }

        if (is_array($this->callback)) {
            $this->reflection = new \ReflectionMethod($this->callback[0], $this->callback[1]);
        } elseif (is_object($this->callback) && !$this->callback instanceof \Closure) {
            $r = new \ReflectionObject($this->callback);
            $this->reflection = $r->getMethod('__invoke');
        } else {
            $this->reflection = new \ReflectionFunction($this->callback);
        }

        return $this->reflection;
    }
}
