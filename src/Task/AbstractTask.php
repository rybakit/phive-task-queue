<?php

namespace Phive\TaskQueue\Task;

abstract class AbstractTask implements Task
{
    private $payload;

    public function __construct($payload = null)
    {
        if (null !== $payload) {
            $this->setPayload($payload);
        }
    }

    public function setPayload($payload)
    {
        $this->payload = $payload;
    }

    /**
     * {@inheritdoc}
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return get_class($this);
    }
}
