<?php

namespace Phive\TaskQueue\Task;

class CallableTask extends AbstractTask implements CallableAware
{
    private $callable;
    private $arguments;

    public function __construct($callable, array $arguments = null)
    {
        $this->callable = $callable;
        $this->arguments = (array) $arguments;
    }

    /**
     * {@inheritdoc}
     */
    public function getCallable()
    {
        return $this->callable;
    }

    /**
     * {@inheritdoc}
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return get_class($this);
    }
}
